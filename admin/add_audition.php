<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// Handle form submission to add a new audition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $society_id = $_POST['society_id'] ?? '';
    $audition_date = $_POST['audition_date'] ?? '';
    $venue = $_POST['venue'] ?? '';
    $status = 'pending';  // Default status

    // Validation (basic checks)
    if (empty($title) || empty($society_id) || empty($audition_date) || empty($venue)) {
        $_SESSION['message'] = "Please fill in all fields.";
        header("Location: add_audition.php");
        exit();
    }

    // Insert the new audition into the database
    $stmt = $pdo->prepare("INSERT INTO auditions (title, society_id, audition_date, venue, status, created_by) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $society_id, $audition_date, $venue, $status, $_SESSION['user_id']]);
    
    $_SESSION['message'] = "Audition added successfully!";
    header("Location: manage_auditions.php");
    exit();
}

// Get all societies for the dropdown
$societies = $pdo->query("SELECT * FROM societies")->fetchAll();

include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Audition</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles1.css"> <!-- CSS linked here -->
</head>
<body>
<div class="admin-container">
    <h1><i class="fas fa-plus"></i> Add New Audition</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="title">Audition Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="society_id">Select Society</label>
            <select name="society_id" id="society_id" class="form-control" required>
                <option value="">-- Select Society --</option>
                <?php foreach ($societies as $society): ?>
                    <option value="<?php echo $society['id']; ?>"><?php echo htmlspecialchars($society['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="audition_date">Audition Date</label>
            <input type="datetime-local" name="audition_date" id="audition_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="venue">Venue</label>
            <input type="text" name="venue" id="venue" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Audition</button>
        <a href="manage_auditions.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Auditions</a>
    </form>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
