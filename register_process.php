<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mynewdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data and perform necessary validations
$emp_id = $_POST['emp_id'];
$password = $_POST['password'];
$employee_name = $_POST['employee_name'];
$department = $_POST['department'];
$mobile_no = $_POST['mobile_no'];
$email_id = $_POST['email_id'];
$salary = $_POST['salary'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = $_POST['address'];

// Set the initial registration status as 'pending'
$registration_status = 'pending';

$sql = "INSERT INTO students (student_id, password, student_name, department, mobile_no, email_id, club, dob, gender, address, status)
        VALUES ('$emp_id', '$password', '$employee_name', '$department', '$mobile_no', '$email_id', '$salary', '$dob', '$gender', '$address', '$registration_status')";

$result = $conn->query($sql);

if ($result) {
    echo "Registration request sent for approval. Please wait for admin confirmation.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
