<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user data
$stmt = $pdo->prepare("SELECT first_name FROM users WHERE id = ?");
if (!$stmt->execute([$user_id])) {
    die("Error fetching user data");
}
$user = $stmt->fetch();

// Get user's societies
$stmt = $pdo->prepare("
    SELECT s.* FROM societies s
    JOIN society_members sm ON s.id = sm.society_id
    WHERE sm.user_id = ?
");
if (!$stmt->execute([$user_id])) {
    die("Error fetching societies");
}
$societies = $stmt->fetchAll();

// Get upcoming events
$stmt = $pdo->prepare("
    SELECT e.*, s.name as society_name FROM events e
    JOIN societies s ON e.society_id = s.id
    WHERE e.event_date > NOW()
    ORDER BY e.event_date ASC
    LIMIT 3
");
if (!$stmt->execute()) {
    die("Error fetching events");
}
$events = $stmt->fetchAll();

// Get upcoming auditions
$stmt = $pdo->prepare("
    SELECT a.*, s.name as society_name FROM auditions a
    JOIN societies s ON a.society_id = s.id
    WHERE a.audition_date > NOW()
    ORDER BY a.audition_date ASC
    LIMIT 3
");
if (!$stmt->execute()) {
    die("Error fetching auditions");
}
$auditions = $stmt->fetchAll();

include '../includes/header.php';
?>

<!-- Rest of your HTML remains the same -->
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
<div class="dashboard-container">
    <h1>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
    <p class="welcome-message">Here's what's happening in your societies</p>

    <div class="dashboard-grid">
        <!-- My Societies Section -->
        <section class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-users"></i> My Societies</h2>
            </div>
            <div class="card-body">
                <?php if (count($societies) > 0): ?>
                    <div class="societies-list">
                        <?php foreach ($societies as $society): ?>
                            <div class="society-item">
                                <div class="society-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="society-info">
                                    <h3><?php echo htmlspecialchars($society['name']); ?></h3>
                                    <p><?php echo htmlspecialchars(substr($society['description'], 0, 50)); ?>...</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <p>You're not a member of any societies yet</p>
                        <a href="societies.php" class="btn btn-primary">Browse Societies</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Upcoming Events Section -->
        <section class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-alt"></i> Upcoming Events</h2>
            </div>
            <div class="card-body">
                <?php if (count($events) > 0): ?>
                    <div class="events-list">
                        <?php foreach ($events as $event): ?>
                            <div class="event-item">
                                <div class="event-date">
                                    <span class="day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                                    <span class="month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                                </div>
                                <div class="event-info">
                                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                    <p class="society"><?php echo htmlspecialchars($event['society_name']); ?></p>
                                    <p class="time"><i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($event['event_date'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="events.php" class="view-all">View All Events <i class="fas fa-arrow-right"></i></a>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>No upcoming events</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Upcoming Auditions Section -->
        <section class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-microphone-alt"></i> Upcoming Auditions</h2>
            </div>
            <div class="card-body">
                <?php if (count($auditions) > 0): ?>
                    <div class="auditions-list">
                        <?php foreach ($auditions as $audition): ?>
                            <div class="audition-item">
                                <div class="audition-date">
                                    <span class="day"><?php echo date('d', strtotime($audition['audition_date'])); ?></span>
                                    <span class="month"><?php echo date('M', strtotime($audition['audition_date'])); ?></span>
                                </div>
                                <div class="audition-info">
                                    <h3><?php echo htmlspecialchars($audition['title']); ?></h3>
                                    <p class="society"><?php echo htmlspecialchars($audition['society_name']); ?></p>
                                    <p class="time"><i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($audition['audition_date'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="auditions.php" class="view-all">View All Auditions <i class="fas fa-arrow-right"></i></a>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-microphone-slash"></i>
                        <p>No upcoming auditions</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
                </body>
                </html>

<?php include '../includes/footer.php'; ?>