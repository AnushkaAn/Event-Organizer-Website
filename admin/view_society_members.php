<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_societies.php");
    exit();
}

$society_id = $_GET['id'];

// Get society name (fixed query)
$stmt = $pdo->prepare("SELECT name FROM societies WHERE id = ?");
$stmt->execute([$society_id]);
$society = $stmt->fetch();

if (!$society) {
    header("Location: manage_societies.php");
    exit();
}

// Get members (fixed query)
$stmt = $pdo->prepare("
    SELECT u.id, u.username, u.first_name, u.last_name, u.email, u.roll_number, sm.joined_date
    FROM society_members sm
    JOIN users u ON sm.user_id = u.id
    WHERE sm.society_id = ?
    ORDER BY sm.joined_date DESC
");
$stmt->execute([$society_id]);
$members = $stmt->fetchAll();

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
    <h1><i class="fas fa-users"></i> Members of <?php echo htmlspecialchars($society['name']); ?></h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Roll No.</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($members) > 0): ?>
                    <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?php echo $member['id']; ?></td>
                        <td><?php echo htmlspecialchars($member['first_name'] . ' ' . $member['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($member['username']); ?></td>
                        <td><?php echo htmlspecialchars($member['email']); ?></td>
                        <td><?php echo htmlspecialchars($member['roll_number']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($member['joined_date'])); ?></td>
                        <td>
                            <a href="remove_member.php?society_id=<?php echo $society_id; ?>&user_id=<?php echo $member['id']; ?>" 
                               class="btn btn-danger btn-sm" onclick="return confirm('Remove this member?')">
                               <i class="fas fa-user-minus"></i> Remove
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="empty-state">
                                <i class="fas fa-users-slash fa-3x mb-3"></i>
                                <h3>No Members Found</h3>
                                <p>This society currently has no members.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="text-center mt-4">
        <a href="manage_societies.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Societies
        </a>
    </div>
</div>
                </body>
                </html>

<?php include '../includes/footer.php'; ?>