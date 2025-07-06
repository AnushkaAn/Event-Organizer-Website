<?php
$conn = mysqli_connect(
    getenv("MYSQL_ADDON_HOST"),
    getenv("MYSQL_ADDON_USER"),
    getenv("MYSQL_ADDON_PASSWORD"),
    getenv("MYSQL_ADDON_DB")
);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
