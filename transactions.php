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

        h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            max-width: 600px;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid white;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #004d4d;
        }

        tr:nth-child(even) {
            background-color: #006666;
        }

        tr:nth-child(odd) {
            background-color: #004d4d;
        }
    </style>
";

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Blood Donation System - Transfusions</title>
    $commonStyles
</head>
<body>
    <h1>Blood Transfusion Information</h1>";

// Your PHP logic to fetch and display transaction data goes here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bbmsfinal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CALL GetTransactionData()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Donor ID</th>
                <th>Donor Name</th>
                <th>Receiver ID</th>
                <th>Receiver Name</th>
                <th>Blood Group</th>
                <th>Transaction Date</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['Donor ID']}</td>
                <td>{$row['Donor Name']}</td>
                <td>{$row['Receiver ID']}</td>
                <td>{$row['Receiver Name']}</td>
                <td>{$row['Blood Group']}</td>
                <td>{$row['Transaction Date']}</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No transactions found.";
}

$conn->close();

echo "</body>
</html>";
?>
