<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user's societies with event and audition counts
$societies = $pdo->prepare("
    SELECT s.*, 
           (SELECT COUNT(*) FROM events WHERE society_id = s.id AND event_date > NOW()) as upcoming_events,
           (SELECT COUNT(*) FROM auditions WHERE society_id = s.id AND audition_date > NOW()) as upcoming_auditions
    FROM societies s
    JOIN society_members sm ON s.id = sm.society_id
    WHERE sm.user_id = ?
")->execute([$user_id])->fetchAll();

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
    <h1><i class="fas fa-users"></i> My Societies</h1>
    
    <?php if (count($societies) > 0): ?>
    <div class="societies-grid">
        <?php foreach ($societies as $society): ?>
        <div class="society-card">
            <div class="society-image">
                <?php if ($society['image_path']): ?>
                    <img src="../assets/images/<?php echo htmlspecialchars($society['image_path']); ?>" alt="<?php echo htmlspecialchars($society['name']); ?>">
                <?php else: ?>
                    <div class="image-placeholder">
                        <i class="fas fa-users"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="society-info">
                <h2><?php echo htmlspecialchars($society['name']); ?></h2>
                <p class="society-description"><?php echo htmlspecialchars(substr($society['description'], 0, 200)); ?>...</p>
                
                <div class="society-stats">
                    <div class="stat">
                        <i class="fas fa-calendar-alt"></i>
                        <span><?php echo $society['upcoming_events']; ?> Events</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-microphone-alt"></i>
                        <span><?php echo $society['upcoming_auditions']; ?> Auditions</span>
                    </div>
                </div>
                
                <div class="society-actions">
                    <a href="society_details.php?id=<?php echo $society['id']; ?>" class="btn btn-primary">
                        <i class="fas fa-info-circle"></i> Details
                    </a>
                    <a href="events.php?society=<?php echo $society['id']; ?>" class="btn btn-secondary">
                        <i class="fas fa-calendar"></i> Events
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h3>Not a member of any societies yet</h3>
            <p>Join societies to participate in events and activities</p>
            <a href="societies.php" class="btn btn-primary">
                <i class="fas fa-search"></i> Browse Societies
            </a>
        </div>
    <?php endif; ?>
</div>
    </body>
    </html>

<?php include '../includes/footer.php'; ?>