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
    <header>
        <div class="container">
            <div class="logo">
                <h1>College Societies Hub</h1>
            </div>
            <nav>
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="../admin/dashboard.php">Admin Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="../user/dashboard.php">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="../user/societies.php">Societies</a></li>
                        <li><a href="../user/events.php">Events</a></li>
                        <li><a href="../user/auditions.php">Auditions</a></li>
                        <li><a href="../auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="../index.php">Home</a></li>
                        <li><a href="../auth/login.php">Login</a></li>
                        <li><a href="../auth/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container">
