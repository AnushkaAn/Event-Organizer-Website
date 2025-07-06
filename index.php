<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Societies Hub</title>
    <link rel="stylesheet" href="assets/css/styles1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            
            <div class="logo">
                <img src="assets/images/logo.png" alt="College Societies Hub">
                <h1>College Societies Hub</h1>
                <img src="assets/images/college.jpg" alt="College Photo">
            </div>
            
            <nav class="main-nav">
                <ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li><a href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="user/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="user/societies.php"><i class="fas fa-users"></i> Societies</a></li>
                        <li><a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php" class="active"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="auth/register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="mobile-menu-btn">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Discover Your College Communities</h1>
                <p>Join societies, attend events, and make the most of your college experience</p>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="hero-buttons">
                        <a href="auth/register.php" class="btn btn-primary">Get Started</a>
                        <a href="user/societies.php" class="btn btn-secondary">Browse Societies</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="featured-societies">
        <div class="container">
            <h2>Featured Societies</h2>
            <div class="societies-grid">
                <div class="society-card">
                    <div class="society-image">
                        <img src="assets/images/society1.jpg" alt="Drama Society">
                    </div>
                    <div class="society-info">
                        <h3>Drama Society</h3>
                        <p>Explore your acting talents and participate in college productions.</p>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="user/society_details.php?id=1" class="btn btn-small">View Details</a>
                        <?php else: ?>
                            <a href="auth/login.php" class="btn btn-small">Login to View</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="society-card">
    <div class="society-image">
        <img src="assets/images/society2.jpg" alt="Music Club">
    </div>
    <div class="society-info">
        <h3>Music Club</h3>
        <p>For musicians and music lovers to collaborate and perform.</p>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="https://www.jiit.ac.in/crescendo-music-hub" class="btn btn-small" target="_blank">View Details</a>
        <?php else: ?>
            <a href="auth/login.php" class="btn btn-small">Login to View</a>
        <?php endif; ?>
    </div>
</div>

                
                <div class="society-card">
                    <div class="society-image">
                        <img src="assets/images/society3.jpg" alt="Debating Society">
                    </div>
                    <div class="society-info">
                        <h3>Debating Society</h3>
                        <p>Hone your public speaking and critical thinking skills.</p>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="user/society_details.php?id=3" class="btn btn-small">View Details</a>
                        <?php else: ?>
                            <a href="auth/login.php" class="btn btn-small">Login to View</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="feature-box">
                <i class="fas fa-users"></i>
                <h3>Join Societies</h3>
                <p>Discover and join various student societies that match your interests.</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-calendar-alt"></i>
                <h3>Attend Events</h3>
                <p>Never miss out on society events, workshops, and competitions.</p>
            </div>
            <div class="feature-box">
                <i class="fas fa-microphone"></i>
                <h3>Auditions</h3>
                <p>Apply for auditions and showcase your talents to society heads.</p>
            </div>
        </div>
    </section>

    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p>College Societies Hub connects students with campus organizations and activities.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="user/societies.php">Societies</a></li>
                        <li><a href="auth/login.php">Login</a></li>
                        <li><a href="auth/register.php">Register</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: info@societieshub.edu</p>
                    <p>Phone: (123) 456-7890</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> College Societies Hub. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>