<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// Handle audition approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $audition_id = $_POST['audition_id'];
        $stmt = $pdo->prepare("UPDATE auditions SET status = 'approved', processed_by = ?, processed_date = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['user_id'], $audition_id]);
        $_SESSION['message'] = "Audition approved successfully";
    } elseif (isset($_POST['reject'])) {
        $audition_id = $_POST['audition_id'];
        $stmt = $pdo->prepare("UPDATE auditions SET status = 'rejected', processed_by = ?, processed_date = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['user_id'], $audition_id]);
        $_SESSION['message'] = "Audition rejected successfully";
    }
    header("Location: manage_auditions.php");
    exit();
}

// Get all auditions with society and creator info
$auditions = $pdo->query("
    SELECT 
        a.*, 
        s.name as society_name, 
        u.username as creator_name,
        adm.username as processed_by_name
    FROM auditions a
    JOIN societies s ON a.society_id = s.id
    JOIN users u ON a.created_by = u.id
    LEFT JOIN admins adm ON a.processed_by = adm.id
    ORDER BY a.audition_date DESC
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
<div class="admin-container">
    <h1><i class="fas fa-microphone-alt"></i> Manage Auditions</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    
    <!-- Button to add a new audition -->
    <a href="add_audition.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Add New Audition</a>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Society</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditions as $audition): ?>
                <tr>
                    <td><?php echo $audition['id']; ?></td>
                    <td><?php echo htmlspecialchars($audition['title']); ?></td>
                    <td><?php echo htmlspecialchars($audition['society_name']); ?></td>
                    <td><?php echo date('M j, Y g:i A', strtotime($audition['audition_date'])); ?></td>
                    <td><?php echo htmlspecialchars($audition['venue']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $audition['status'] ?? 'pending'; ?>">
                            <?php echo ucfirst($audition['status'] ?? 'pending'); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($audition['creator_name']); ?></td>
                    <td>
                        <?php if (($audition['status'] ?? 'pending') === 'pending'): ?>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="audition_id" value="<?php echo $audition['id']; ?>">
                            <button type="submit" name="approve" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="audition_id" value="<?php echo $audition['id']; ?>">
                            <button type="submit" name="reject" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                        <?php else: ?>
                            <span class="text-muted">Processed by <?php echo htmlspecialchars($audition['processed_by_name'] ?? 'Admin'); ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
