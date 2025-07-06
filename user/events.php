<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

// Get all approved upcoming events
$events = $pdo->query("
    SELECT e.*, s.name as society_name, s.image_path as society_image
    FROM events e
    JOIN societies s ON e.society_id = s.id
    WHERE e.event_date > NOW() AND e.status = 'approved'
    ORDER BY e.event_date ASC
")->fetchAll();

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
<div class="user-container">
    <h1><i class="fas fa-calendar-alt"></i> Upcoming Events</h1>
    
    <?php if (count($events) > 0): ?>
    <div class="event-grid">
        <?php foreach ($events as $event): ?>
        <div class="event-card">
            <div class="event-image">
                <?php if ($event['image_path']): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($event['image_path']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>">
                <?php else: ?>
                    <div class="image-placeholder">
                        <i class="fas fa-calendar"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="event-body">
                <div class="event-date">
                    <span class="day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                    <span class="month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                </div>
                <div class="event-info">
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <p class="society"><i class="fas fa-users"></i> <?php echo htmlspecialchars($event['society_name']); ?></p>
                    <p class="time"><i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($event['event_date'])); ?></p>
                    <p class="venue"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['venue']); ?></p>
                    <div class="event-description">
                        <p><?php echo htmlspecialchars($event['description']); ?></p>
                    </div>
                </div>
            </div>
            <div class="event-footer">
                <div class="countdown" data-event-date="<?php echo $event['event_date']; ?>">
                    <i class="fas fa-hourglass-half"></i> 
                    <span class="countdown-text">Loading...</span>
                </div>
                <a href="event_details.php?id=<?php echo $event['id']; ?>" class="btn btn-primary">
                    <i class="fas fa-info-circle"></i> Details
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <h3>No upcoming events</h3>
            <p>Check back later for new events</p>
        </div>
    <?php endif; ?>
</div>

<script>
// Countdown timer for events
document.querySelectorAll('.countdown').forEach(element => {
    const eventDate = new Date(element.dataset.eventDate).getTime();
    
    const updateCountdown = () => {
        const now = new Date().getTime();
        const distance = eventDate - now;
        
        if (distance < 0) {
            element.querySelector('.countdown-text').textContent = 'Event started!';
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        element.querySelector('.countdown-text').textContent = 
            `${days}d ${hours}h ${minutes}m ${seconds}s`;
    };
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>
</body>
</html>

<?php include '../includes/footer.php'; ?>