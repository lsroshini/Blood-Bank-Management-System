<?php

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

        p {
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
";

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Donor Login</title>
    $commonStyles
</head>
<body>";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bbmsfinal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$aadhar_no = $_POST['aadhar'];

$sql = "SELECT * FROM donors WHERE aadhar_number = '$aadhar_no'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    if ($fname === $row['first_name'] && $lname === $row['last_name']) {
        $lastDonated = $row['last_donation_date'];
        $gender = $row['gender'];

        if ($lastDonated === null) {
            echo "Blood can be donated.";
        } else {
            $lastDonatedDate = new DateTime($lastDonated);
            $currentDate = new DateTime();

            if (($gender === 'Female' && $lastDonatedDate->diff($currentDate)->m >= 4)
                || ($gender === 'Male' && $lastDonatedDate->diff($currentDate)->m >= 3)) {
                $sql = "UPDATE donors SET number_of_donations = number_of_donations + 1 WHERE aadhar_number = '$aadhar_no'";
                $conn->query($sql);
                echo "Blood can be donated.";
            } else {
                echo "Blood can't be donated.";
            }
        }
    } else {
        echo "Login failed! Please check your first name and last name.";
    }
} else {
    echo "Login failed! Please check your Aadhar number.";
}

$conn->close();

echo "</body>
</html>";
?>
