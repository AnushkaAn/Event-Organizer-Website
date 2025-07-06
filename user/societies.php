<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Debugging: Show connection status
if (!$pdo) {
    die("Database connection failed");
}

// Fetch all societies with error handling
try {
    $stmt = $pdo->query("SELECT * FROM societies");
    if (!$stmt) {
        throw new Exception("Failed to prepare societies query");
    }
    $societies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die($e->getMessage());
}

// Check which societies the user is already a member of
try {
    $stmt = $pdo->prepare("SELECT society_id FROM society_members WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user_societies = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Error fetching user societies: " . $e->getMessage());
}

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
<h1>All Societies</h1>

<div class="societies-grid">
    <?php if (count($societies) > 0): ?>
        <?php foreach ($societies as $society): ?>
            <div class="society-card">
                <div class="society-image">
                    <?php if (!empty($society['image_path'])): ?>
                        <img src="../assets/images/<?php echo htmlspecialchars($society['image_path']); ?>" alt="<?php echo htmlspecialchars($society['name']); ?>">
                    <?php else: ?>
                        <div class="placeholder-image">
                            <i class="fas fa-users"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="society-info">
                    <h2><?php echo htmlspecialchars($society['name']); ?></h2>
                    <p><?php echo htmlspecialchars(substr($society['description'], 0, 150)); ?>...</p>
                    
                    <?php if (in_array($society['id'], $user_societies)): ?>
                        <p class="member-badge"><i class="fas fa-check-circle"></i> You are a member</p>
                    <?php else: ?>
                        <a href="apply.php?society_id=<?php echo $society['id']; ?>" class="btn btn-primary">Apply to Join</a>
                    <?php endif; ?>
                    
                    <a href="society_details.php?id=<?php echo $society['id']; ?>" class="btn btn-secondary">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <p>No societies found</p>
        </div>
    <?php endif; ?>
</div>
    </body>
    </html>

<?php include '../includes/footer.php'; ?>