<?php
$admin_username = "admin";
$admin_hashed_password = password_hash("1234", PASSWORD_DEFAULT);

$user_input_username = $_POST['username'];
$user_input_password = $_POST['password'];

if ($user_input_username === $admin_username && password_verify($user_input_password, $admin_hashed_password)) {
    $adminPanelStyles = "
        <style>
            /* Admin panel styles */
            body {
                background-color: teal;
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
                color: white;
                font-size: 30px;
                margin-bottom: 50px;
            }

            h3 {
                color: white;
                font-size: 24px;
                margin-bottom: 10px;
            }

            ul {
                list-style-type:none;
                padding: 0;
            }

            li {
                margin: 10px 0;
            }

            button {
                text-decoration: none;
                color: white;
                font-size: 18px;
                padding: 10px 20px;
                background-color: teal;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                transition: background-color 0.6s;
            }

            button:hover {
                background-color: #004d4d; /* Slightly darker green on hover */
            }
        </style>
    ";

    echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Blood Donation System - Admin Panel</title>
            $adminPanelStyles
        </head>
        <body>
            <h2>Welcome, Admin!</h2>
            <h3>Choose an Option</h3>
            <button onclick=\"location.href='donor_registeration.html'\">Donor Registration</button>
            <button onclick=\"location.href='donor_login.html'\">Donor Login</button>
            <button onclick=\"location.href='transfusion.html'\">Transfusion</button>
            <button onclick=\"location.href='BDC.html'\">Create Camp</button>
            <button onclick=\"location.href='camp_donor_form.html'\">Camp Donor Form</button>
            <button onclick=\"location.href='transactions.php'\">Display Transactions</button> <!-- Link to display transactions -->
            <button onclick=\"location.href='stock.php'\">Display Stock</button> <!-- Link to display stock -->
        </body>
        </html>";
} else {
    $errorStyles = "
        <style>
            /* Error styles */
            body {
                background-color: teal;
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
                color: white;
                font-size: 30px;
                margin-bottom: 50px;
            }
        </style>
    ";

    echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Blood Donation System - Error</title>
            $errorStyles
        </head>
        <body>
            <h2>Error: Invalid username or password.</h2>
        </body>
        </html>";
}
?>
