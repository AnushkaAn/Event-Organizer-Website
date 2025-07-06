<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// Handle event approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $event_id = $_POST['event_id'];
        $stmt = $pdo->prepare("UPDATE events SET status = 'approved', processed_by = ?, processed_date = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['user_id'], $event_id]);
        $_SESSION['message'] = "Event approved successfully";
    } elseif (isset($_POST['reject'])) {
        $event_id = $_POST['event_id'];
        $stmt = $pdo->prepare("UPDATE events SET status = 'rejected', processed_by = ?, processed_date = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['user_id'], $event_id]);
        $_SESSION['message'] = "Event rejected successfully";
    }
    header("Location: manage_events.php");
    exit();
}

// Get all events with society and creator info
$events = $pdo->query("
    SELECT 
        e.*, 
        s.name as society_name, 
        u.username as creator_name,
        a.username as processed_by_name
    FROM events e
    JOIN societies s ON e.society_id = s.id
    JOIN users u ON e.created_by = u.id
    LEFT JOIN admins a ON e.processed_by = a.id
    ORDER BY e.event_date DESC
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
    <h1><i class="fas fa-calendar-alt"></i> Manage Events</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    
    <!-- Add Event Button -->
    <a href="add_event.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Add Event</a>
    
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
                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo $event['id']; ?></td>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td><?php echo htmlspecialchars($event['society_name']); ?></td>
                    <td><?php echo date('M j, Y g:i A', strtotime($event['event_date'])); ?></td>
                    <td><?php echo htmlspecialchars($event['venue']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $event['status'] ?? 'pending'; ?>">
                            <?php echo ucfirst($event['status'] ?? 'pending'); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($event['creator_name']); ?></td>
                    <td>
                        <?php if (($event['status'] ?? 'pending') === 'pending'): ?>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" name="approve" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" name="reject" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                        <?php else: ?>
                            <span class="text-muted">Processed by <?php echo htmlspecialchars($event['processed_by_name'] ?? 'Admin'); ?></span>
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
