<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_username = $_POST['admin_username'];

    // Retrieve admin data from the 'admin' table based on the username
    $check_admin = "SELECT * FROM admin WHERE admin_id = '$admin_username'";
    $result = $conn->query($check_admin);

    if ($result->num_rows == 1) {
        // Admin found based on the username
        $_SESSION['admin_username'] = $admin_username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Admin not found";
    }
}

$conn->close();
?>
