<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

if (!isset($_GET['society_id'])) {
    header("Location: societies.php");
    exit();
}

$society_id = $_GET['society_id'];
$user_id = $_SESSION['user_id'];

// Check if society exists with error handling
try {
    $stmt = $pdo->prepare("SELECT * FROM societies WHERE id = ?");
    if (!$stmt->execute([$society_id])) {
        throw new Exception("Failed to execute society query");
    }
    $society = $stmt->fetch();
    
    if (!$society) {
        header("Location: societies.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die($e->getMessage());
}

// Check if user is already a member
try {
    $stmt = $pdo->prepare("SELECT id FROM society_members WHERE user_id = ? AND society_id = ?");
    if (!$stmt->execute([$user_id, $society_id])) {
        throw new Exception("Failed to execute membership check");
    }
    $is_member = $stmt->fetch();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

if ($is_member) {
    header("Location: societies.php");
    exit();
}

// Check for pending requests
try {
    $stmt = $pdo->prepare("SELECT id FROM membership_requests WHERE user_id = ? AND society_id = ? AND status = 'pending'");
    if (!$stmt->execute([$user_id, $society_id])) {
        throw new Exception("Failed to execute pending request check");
    }
    $has_pending = $stmt->fetch();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("INSERT INTO membership_requests (user_id, society_id) VALUES (?, ?)");
        if ($stmt->execute([$user_id, $society_id])) {
            $message = "Your application has been submitted successfully!";
        } else {
            $message = "There was an error submitting your application.";
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
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
<div class="apply-container">
    <h1>Apply to Join: <?php echo htmlspecialchars($society['name']); ?></h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
        <a href="societies.php" class="btn btn-primary">Back to Societies</a>
    <?php elseif ($has_pending): ?>
        <div class="alert alert-info">
            You already have a pending request to join this society.
        </div>
        <a href="societies.php" class="btn btn-primary">Back to Societies</a>
    <?php else: ?>
        <div class="society-description">
            <p><?php echo htmlspecialchars($society['description']); ?></p>
        </div>
        
        <form method="POST" class="apply-form">
            <p>Are you sure you want to apply to join this society?</p>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Submit Application</button>
                <a href="societies.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php endif; ?>
</div>
    </body>
    </html>

<?php include '../includes/footer.php'; ?>