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
    <title>Blood Donation Camp</title>
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

$drive_name = $_POST['drive_name'];
$location = $_POST['location'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$sql = "INSERT INTO blood_donation_camp (drive_name, location, start_date, end_date) 
        VALUES ('$drive_name', '$location', '$start_date', '$end_date')";

if ($conn->query($sql) === TRUE) {
    echo "Blood donation camp created successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

echo "</body>
</html>";
?>
