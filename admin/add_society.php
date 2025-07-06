<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $image_path = null;

    // Validate inputs
    if (empty($name)) {
        $error = 'Society name is required';
    } else {
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../assets/images/societies/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Validate image
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $file_type = $_FILES['image']['type'];
            
            if (in_array($file_type, $allowed_types)) {
                $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $new_filename = 'society_' . time() . '.' . $file_ext;
                $target_file = $upload_dir . $new_filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_path = 'societies/' . $new_filename;
                } else {
                    $error = 'Failed to upload image';
                }
            } else {
                $error = 'Only JPG, PNG, and GIF images are allowed';
            }
        }

        if (empty($error)) {
            // Insert new society
            $stmt = $pdo->prepare("INSERT INTO societies (name, description, image_path) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $description, $image_path])) {
                $success = 'Society created successfully!';
                $_SESSION['message'] = $success;
                header("Location: manage_societies.php");
                exit();
            } else {
                $error = 'Failed to create society';
            }
        }
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
<div class="admin-container">
    <h1><i class="fas fa-plus-circle"></i> Add New Society</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" enctype="multipart/form-data" class="society-form">
        <div class="form-group">
            <label for="name">Society Name *</label>
            <input type="text" id="name" name="name" class="form-control" required
                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" class="form-control" rows="5" required><?php 
                echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; 
            ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="image">Society Image</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Optional. Max size 2MB. Allowed types: JPG, PNG, GIF</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Society
            </button>
            <a href="manage_societies.php" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
    </body>
    </html>

<?php include '../includes/footer.php'; ?>