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
// Retrieve login data and perform necessary validations
$emp_id = $_POST['emp_id'];
$password = $_POST['password'];
// Check if the provided credentials are valid
$sql = "SELECT * FROM students WHERE student_id='$emp_id' AND password='$password'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// Login successful
// Redirect to welcome.php or perform other actions as needed
header("Location: welcome.php");
} else {
// Invalid login credentials
echo "Invalid login credentials";
}
$conn->close();
?>