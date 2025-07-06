<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $request_id = $_POST['request_id'];
        
        // Start transaction
        $pdo->beginTransaction();
        
        try {
            // 1. Get the request details
            $stmt = $pdo->prepare("SELECT user_id, society_id FROM membership_requests WHERE id = ?");
            $stmt->execute([$request_id]);
            $request = $stmt->fetch();
            
            if (!$request) {
                throw new Exception("Request not found");
            }
            
            // 2. Add to society_members
            $stmt = $pdo->prepare("INSERT INTO society_members (user_id, society_id) VALUES (?, ?)");
            $stmt->execute([$request['user_id'], $request['society_id']]);
            
            // 3. Update request status
            $stmt = $pdo->prepare("UPDATE membership_requests SET status = 'approved', processed_by = ?, processed_date = NOW() WHERE id = ?");
            $stmt->execute([$_SESSION['user_id'], $request_id]);
            
            // Commit transaction
            $pdo->commit();
            
            $_SESSION['message'] = "Request approved and member added successfully";
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        
    } elseif (isset($_POST['reject'])) {
        $request_id = $_POST['request_id'];
        $stmt = $pdo->prepare("UPDATE membership_requests SET status = 'rejected', processed_by = ?, processed_date = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['user_id'], $request_id]);
        $_SESSION['message'] = "Request rejected successfully";
    }
    
    header("Location: manage_requests.php");
    exit();
}

// Get all pending requests
$requests = $pdo->query("
    SELECT mr.*, u.username as user_name, u.email as user_email, 
           s.name as society_name, a.username as processed_by_name
    FROM membership_requests mr
    JOIN users u ON mr.user_id = u.id
    JOIN societies s ON mr.society_id = s.id
    LEFT JOIN admins a ON mr.processed_by = a.id
    ORDER BY mr.request_date DESC
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
    <h1>Manage Membership Requests</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Society</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Processed By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo $request['id']; ?></td>
                    <td>
                        <?php echo htmlspecialchars($request['user_name']); ?><br>
                        <small><?php echo htmlspecialchars($request['user_email']); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($request['society_name']); ?></td>
                    <td><?php echo date('M j, Y g:i A', strtotime($request['request_date'])); ?></td>
                    <td>
                        <span class="status-badge <?php echo $request['status']; ?>">
                            <?php echo ucfirst($request['status']); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($request['processed_by_name']): ?>
                            <?php echo htmlspecialchars($request['processed_by_name']); ?><br>
                            <small><?php echo date('M j, Y', strtotime($request['processed_date'])); ?></small>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($request['status'] === 'pending'): ?>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="approve" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <form method="POST" style="display: inline-block;">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <button type="submit" name="reject" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                        <?php else: ?>
                            <span class="text-muted">Processed</span>
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