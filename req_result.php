<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$commonStyles = "
    <style>
        /* Common styles */
        body {
            background-color: teal;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        table {
            width: 80%;
            margin-top: 20px;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid white;
        }

        th {
            background-color: #004d4d;
        }

        td {
            background-color: #006666;
        }
    </style>
";

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Blood Donation System - Donor Matching</title>
    $commonStyles
</head>
<body>
    <h2>Donor Matching Results</h2>";

// Your PHP logic goes here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bbmsfinal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$receiver_id = $_POST['receiver_id'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];

$sql = "SELECT * FROM receivers WHERE receiver_id = '$receiver_id'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $blood_group = $_POST['blood_group'];

    if ($fname === $row['first_name'] && $lname === $row['last_name'] && $blood_group === $row['blood_group']) {
        // Verification successful, proceed to get donors list
        $threeMonthsAgo = date('Y-m-d', strtotime('-3 months'));
        $sql = "SELECT * FROM donors WHERE blood_group = '$blood_group' AND (last_donation_date IS NULL OR last_donation_date < '$threeMonthsAgo')";
        $donors_result = $conn->query($sql);

        if ($donors_result->num_rows > 0) {
            echo "<h3>Matching Donors:</h3>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Phone</th><th>Gender</th><th>City</th><th>Locality</th></tr>";
            while ($donor_row = $donors_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$donor_row['first_name']} {$donor_row['last_name']}</td>";
                echo "<td>{$donor_row['phone']}</td>";
                echo "<td>{$donor_row['gender']}</td>";
                echo "<td>{$donor_row['city']}</td>";
                echo "<td>{$donor_row['locality']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No matching donors found.</p>";
        }
    } else {
        echo "<p>Verification failed! Please check your details.</p>";
    }
} else {
    echo "<p>Verification failed! Please check your details.</p>";
}

$conn->close();

echo "</body>
</html>";
?>
