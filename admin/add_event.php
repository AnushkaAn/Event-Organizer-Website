<?php
require_once '../includes/auth_check.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../user/dashboard.php");
    exit();
}

// Handle event creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $society_id = $_POST['society_id'];
    $event_date = $_POST['event_date'];
    $venue = $_POST['venue'];

    $stmt = $pdo->prepare("INSERT INTO events (title, society_id, event_date, venue, created_by, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$title, $society_id, $event_date, $venue, $_SESSION['user_id']]);

    $_SESSION['message'] = "Event added successfully";
    header("Location: manage_events.php");
    exit();
}

// Get societies for the dropdown
$societies = $pdo->query("SELECT * FROM societies")->fetchAll();

include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles1.css">
</head>
<body>
<div class="admin-container">
    <h1><i class="fas fa-plus"></i> Add New Event</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="society_id">Society</label>
            <select id="society_id" name="society_id" class="form-control" required>
                <option value="">Select Society</option>
                <?php foreach ($societies as $society): ?>
                    <option value="<?php echo $society['id']; ?>"><?php echo htmlspecialchars($society['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="event_date">Event Date and Time</label>
            <input type="datetime-local" id="event_date" name="event_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="venue">Venue</label>
            <input type="text" id="venue" name="venue" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Event</button>
    </form>
</div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
