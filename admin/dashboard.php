<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// Get counts for dashboard
$societies_count = $pdo->query("SELECT COUNT(*) FROM societies")->fetchColumn();
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pending_requests = $pdo->query("SELECT COUNT(*) FROM membership_requests WHERE status = 'pending'")->fetchColumn();
$upcoming_events = $pdo->query("SELECT COUNT(*) FROM events WHERE event_date > NOW()")->fetchColumn();

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Societies Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles1.css"> <!-- CSS linked here -->
</head>
<body>

<h1>Admin Dashboard</h1>
<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Societies</h3>
        <p><?php echo $societies_count; ?></p>
    </div>
    <div class="stat-card">
        <h3>Users</h3>
        <p><?php echo $users_count; ?></p>
    </div>
    <div class="stat-card">
        <h3>Pending Requests</h3>
        <p><?php echo $pending_requests; ?></p>
    </div>
    <div class="stat-card">
        <h3>Upcoming Events</h3>
        <p><?php echo $upcoming_events; ?></p>
    </div>
</div>

<div class="quick-links">
    <h2>Quick Actions</h2>
    <ul>
        <li><a href="manage_societies.php">Manage Societies</a></li>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_requests.php">Manage Membership Requests</a></li>
        <li><a href="manage_events.php">Manage Events</a></li>
        <li><a href="manage_auditions.php">Manage Auditions</a></li>
    </ul>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>