<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

if (!isset($_GET['society_id']) || !isset($_GET['user_id'])) {
    header("Location: manage_societies.php");
    exit();
}

$society_id = $_GET['society_id'];
$user_id = $_GET['user_id'];

// Remove member from society
$stmt = $pdo->prepare("DELETE FROM society_members WHERE society_id = ? AND user_id = ?");
$stmt->execute([$society_id, $user_id]);

$_SESSION['message'] = "Member removed successfully";
header("Location: view_society_members.php?id=" . $society_id);
exit();
?>