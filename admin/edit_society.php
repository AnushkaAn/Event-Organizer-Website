<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// Check if society ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_societies.php");
    exit();
}

$society_id = $_GET['id'];

// Fetch society data
$stmt = $pdo->prepare("SELECT * FROM societies WHERE id = ?");
$stmt->execute([$society_id]);
$society = $stmt->fetch();

if (!$society) {
    header("Location: manage_societies.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    
    // Handle image upload
    $image_path = $society['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/societies/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_filename = 'society_' . $society_id . '_' . time() . '.' . $file_ext;
        $target_file = $upload_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Delete old image if exists
            if ($image_path && file_exists('../assets/images/' . $image_path)) {
                unlink('../assets/images/' . $image_path);
            }
            $image_path = 'societies/' . $new_filename;
        }
    }
    
    // Update society in database
    $stmt = $pdo->prepare("UPDATE societies SET name = ?, description = ?, image_path = ? WHERE id = ?");
    $stmt->execute([$name, $description, $image_path, $society_id]);
    
    $_SESSION['message'] = "Society updated successfully";
    header("Location: manage_societies.php");
    exit();
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
<div class="admin-container">
    <h1>Edit Society: <?php echo htmlspecialchars($society['name']); ?></h1>
    
    <form method="POST" enctype="multipart/form-data" class="society-form">
        <div class="form-group">
            <label for="name">Society Name</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?php echo htmlspecialchars($society['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" 
                      rows="5" required><?php echo htmlspecialchars($society['description']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="image">Society Image</label>
            <?php if ($society['image_path']): ?>
                <div class="current-image">
                    <img src="../assets/images/<?php echo htmlspecialchars($society['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($society['name']); ?>" class="society-thumbnail">
                    <p>Current Image</p>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="manage_societies.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
            </body>
            </html>

<?php include '../includes/footer.php'; ?>