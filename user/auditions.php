<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

// Get all approved upcoming auditions
$auditions = $pdo->query("
    SELECT a.*, s.name as society_name, s.image_path as society_image
    FROM auditions a
    JOIN societies s ON a.society_id = s.id
    WHERE a.audition_date > NOW() AND a.status = 'approved'
    ORDER BY a.audition_date ASC
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
    <h1><i class="fas fa-microphone-alt"></i> Upcoming Auditions</h1>
    
    <?php if (count($auditions) > 0): ?>
    <div class="audition-grid">
        <?php foreach ($auditions as $audition): ?>
        <div class="audition-card">
            <div class="audition-header">
                <div class="society-image">
                    <?php if ($audition['society_image']): ?>
                        <img src="../assets/images/<?php echo htmlspecialchars($audition['society_image']); ?>" alt="<?php echo htmlspecialchars($audition['society_name']); ?>">
                    <?php else: ?>
                        <div class="image-placeholder">
                            <i class="fas fa-users"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="audition-title">
                    <h3><?php echo htmlspecialchars($audition['title']); ?></h3>
                    <p class="society-name"><?php echo htmlspecialchars($audition['society_name']); ?></p>
                </div>
            </div>
            <div class="audition-body">
                <div class="audition-date">
                    <i class="fas fa-calendar-day"></i>
                    <?php echo date('M j, Y', strtotime($audition['audition_date'])); ?>
                </div>
                <div class="audition-time">
                    <i class="fas fa-clock"></i>
                    <?php echo date('g:i A', strtotime($audition['audition_date'])); ?>
                </div>
                <div class="audition-venue">
                    <i class="fas fa-map-marker-alt"></i>
                    <?php echo htmlspecialchars($audition['venue']); ?>
                </div>
                <div class="audition-description">
                    <p><?php echo htmlspecialchars($audition['description']); ?></p>
                </div>
            </div>
            <div class="audition-footer">
                <div class="countdown" data-audition-date="<?php echo $audition['audition_date']; ?>">
                    <i class="fas fa-hourglass-half"></i> 
                    <span class="countdown-text">Loading...</span>
                </div>
                <a href="apply_audition.php?id=<?php echo $audition['id']; ?>" class="btn btn-primary">
                    <i class="fas fa-pencil-alt"></i> Apply Now
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-microphone-slash"></i>
            <h3>No upcoming auditions</h3>
            <p>Check back later for new audition opportunities</p>
        </div>
    <?php endif; ?>
</div>

<script>
// Countdown timer for auditions
document.querySelectorAll('.countdown').forEach(element => {
    const auditionDate = new Date(element.dataset.auditionDate).getTime();
    
    const updateCountdown = () => {
        const now = new Date().getTime();
        const distance = auditionDate - now;
        
        if (distance < 0) {
            element.querySelector('.countdown-text').textContent = 'Audition started!';
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