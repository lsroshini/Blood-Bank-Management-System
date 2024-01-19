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
    <title>Transfusion</title>
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

// Get data from the HTML form
$donorId = $_POST['donor_id'];
$receiverId = $_POST['receiver_id'];
$bloodGroup = $_POST['blood_group'];
$transactionDate = date("Y-m-d"); // Current date

// Insert into the transactions table
$sqlTransaction = "INSERT INTO transactions (donor_id, receiver_id, transaction_date) VALUES ('$donorId', '$receiverId', '$transactionDate')";
$conn->query($sqlTransaction);

// Update the donor's last donation date and increment the number of donations
$sqlUpdateDonor = "UPDATE donors SET last_donation_date = '$transactionDate', number_of_donations = number_of_donations + 1 WHERE donor_id = '$donorId'";
$conn->query($sqlUpdateDonor);

echo "Transfusion recorded successfully!";
// Close the database connection

$conn->close();

echo "</body>
</html>";
?>
