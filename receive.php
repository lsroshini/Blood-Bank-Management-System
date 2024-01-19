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
    <title>Receiver Registration</title>
    $commonStyles
</head>
<body>";

// Define your database connection information
$host = "localhost";
$username = "root";
$password = "";
$database = "bbmsfinal";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from the form
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$bloodGroup = $_POST['blood_group'];
$dateOfBirth = $_POST['date_of_birth'];
$phoneNumber = $_POST['phone_number'];
$gender = $_POST['gender'];
$guardianName = $_POST['guardian_name'];
$guardianPhoneNumber = $_POST['guardian_phone_number'];

// SQL query to insert data into the receiver table
$sql = "INSERT INTO receivers (first_name, last_name, blood_group, dob, phone, gender, guardian_name, guardian_phone) 
        VALUES ('$firstName', '$lastName', '$bloodGroup', '$dateOfBirth', '$phoneNumber', '$gender', '$guardianName', '$guardianPhoneNumber')";

if ($conn->query($sql) === TRUE) {
    $receiverId = $conn->insert_id; // Get the ID of the inserted receiver
    echo "Receiver registration successful! Your Receiver ID is: $receiverId";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();

echo "</body>
</html>";
?>