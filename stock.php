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
    <title>Blood Donation System - Stock</title>
    $commonStyles
</head>
<body>
    <h1>Stock Details</h1>";

// Your PHP logic to fetch and display stock data goes here
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

$sql = "CALL GetStockDetails()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Blood Group</th>
                <th>Units Available</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['blood_group']}</td>
                <td>{$row['units_available']}</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No stock details found.";
}

$conn->close();

echo "</body>
</html>";
?>
