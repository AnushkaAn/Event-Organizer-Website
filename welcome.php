<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mynewdb"; // Use the new database name

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assume the user is already authenticated and fetch details
$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
        }

        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 4px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: lightseagreen;
            color: white;
        }

        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            margin-top: 20px;
            display: inline-block;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Welcome!</h2>
    <table>
        <tr>
            <th>Student_id</th>
            <th>Student_name</th>
            <th>Department</th>
            <th>Mobile_no</th>
            <th>Email_id</th>
            <th>Club</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Hobbies</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['student_id'] . "</td>";
            echo "<td>" . $row['student_name'] . "</td>";
            echo "<td>" . $row['department'] . "</td>";
            echo "<td>" . $row['mobile_no'] . "</td>";
            echo "<td>" . $row['email_id'] . "</td>";
            echo "<td>" . $row['club'] . "</td>";
            echo "<td>" . $row['dob'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <a href="login.php">Logout</a>
</body>
</html>
