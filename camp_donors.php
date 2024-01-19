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
    <title>Blood Donation Camp Donors</title>
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

$aadhar = $_POST['aadhar'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$drive_id = $_POST['drive_id'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];
$blood_group = $_POST['blood_group'];
$donated_before = $_POST['donated_before'];
$last_donation_date = ($donated_before === 'yes') ? $_POST['last_donation_date'] : null;

// Check if current date is greater than the end date of the blood donation camp
$camp_end_date_sql = "SELECT end_date FROM blood_donation_camp WHERE drive_id = '$drive_id'";
$camp_end_date_result = $conn->query($camp_end_date_sql);

if ($camp_end_date_result->num_rows > 0) {
    $camp_end_date_row = $camp_end_date_result->fetch_assoc();
    $camp_end_date = $camp_end_date_row['end_date'];

    if (strtotime(date("Y-m-d")) > strtotime($camp_end_date)) {
        die("Error: Blood donation camp has ended. Cannot accept donations.");
    }
}

// Calculate age based on date of birth
$today = new DateTime();
$birthdate = new DateTime($dob);
$age = $today->diff($birthdate)->y;

// Check if age is between 18 and 65
if ($age < 18 || $age > 65) {
    die("Error: Donor must be between 18 and 65 years old to donate blood.");
}

// Check if the last donation date is within the last 3 months
if ($last_donation_date !== null) {
    $last_donation_date_timestamp = strtotime($last_donation_date);
    $threeMonthsAgoTimestamp = strtotime('-3 months');

    if ($last_donation_date_timestamp > $threeMonthsAgoTimestamp) {
        die("Error: Last donation must be at least 3 months ago.");
    }
}

// Check if the donor already exists in blood_camp_donors table
$check_donor_sql = "SELECT * FROM blood_camp_donors WHERE aadhar_number = '$aadhar'";
$check_donor_result = $conn->query($check_donor_sql);

if ($check_donor_result->num_rows > 0) {
    // Donor already exists, add to camp_donations_record
    $camp_donation_record_sql = "INSERT INTO camp_donations_record (drive_id, donor_aadhar, donation_date) 
                                VALUES ('$drive_id', '$aadhar', CURRENT_DATE)";

    if ($conn->query($camp_donation_record_sql) === TRUE) {
        echo "Camp donation record added for existing donor successfully.";
    } else {
        echo "Error: " . $camp_donation_record_sql . "<br>" . $conn->error;
    }
} else {
    // Donor does not exist, add to blood_camp_donors and camp_donations_record
    $insert_donor_sql = "INSERT INTO blood_camp_donors (aadhar_number, fname, lname, gender, phone, dob, blood_group) 
                        VALUES ('$aadhar', '$fname', '$lname', '$gender', '$phone', '$dob', '$blood_group')";

    if ($conn->query($insert_donor_sql) === TRUE) {
        echo "Camp donor registered successfully.";
    } else {
        echo "Error: " . $insert_donor_sql . "<br>" . $conn->error;
    }

    $camp_donation_record_sql = "INSERT INTO camp_donations_record (drive_id, donor_aadhar, donation_date) 
                                VALUES ('$drive_id', '$aadhar', CURRENT_DATE)";

    if ($conn->query($camp_donation_record_sql) === TRUE) {
        echo "Camp donation record added for new donor successfully.";
    } else {
        echo "Error: " . $camp_donation_record_sql . "<br>" . $conn->error;
    }
}

$conn->close();

echo "</body>
</html>";
?>
