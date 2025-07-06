<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// Handle society deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM societies WHERE id = ?")->execute([$id]);
    header("Location: manage_societies.php");
    exit();
}

// Fetch all societies
$societies = $pdo->query("SELECT * FROM societies")->fetchAll();

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
<h1>Manage Societies</h1>
<a href="add_society.php" class="btn">Add New Society</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($societies as $society): ?>
        <tr>
            <td><?php echo $society['id']; ?></td>
            <td><?php echo htmlspecialchars($society['name']); ?></td>
            <td><?php echo htmlspecialchars(substr($society['description'], 0, 50)); ?>...</td>
            <td>
                <a href="edit_society.php?id=<?php echo $society['id']; ?>" class="btn">Edit</a>
                <a href="manage_societies.php?delete=<?php echo $society['id']; ?>" class="btn danger" onclick="return confirm('Are you sure?')">Delete</a>
                <a href="view_society_members.php?id=<?php echo $society['id']; ?>" class="btn">View Members</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
        </body>
        </html>

<?php include '../includes/footer.php'; ?>