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
    <title>Donor Registeration</title>
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
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$gender = $_POST['gender'];
$phone_no = $_POST['phone'];
$city = $_POST['city'];
$locality = $_POST['locality'];
$blood_group = $_POST['blood-group'];
$dob = $_POST['dob'];
$already_donated = $_POST['alreadyDonated'];
$email = $_POST['email'];
$last_donated = ($already_donated === 'yes') ? $_POST['last'] : null;

// Calculate age based on date of birth
$today = new DateTime();
$birthdate = new DateTime($dob);
$age = $today->diff($birthdate)->y;

if ($age < 18 || $age > 65) {
    echo "Sorry, you must be between 18 and 65 years old to register as a blood donor.";
} else {
    $sql = $conn->prepare("INSERT INTO donors (aadhar_number, first_name, last_name, gender, phone, city, locality, blood_group, dob, last_donation_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters to the prepared statement
    $sql->bind_param("ssssssssss", $aadhar_no, $fname, $lname, $gender, $phone_no, $city, $locality, $blood_group, $dob, $last_donated);

    if ($sql->execute()) {
        $donor_id = $sql->insert_id; // Get the ID of the inserted donor
        echo "Donor registered successfully. Your Donor ID is: $donor_id";
    } else {
        echo "Error: " . $sql->error;
    }
    
    $sql->close();
}

$conn->close();

echo "</body>
</html>";
?>
