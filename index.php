<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="styleindex.css">
</head>
<body>
    <header>
        <h1>Welcome to the Event Management System</h1>
    </header>

    <main>
        <div class="welcome-message">
            <h2>Welcome to the Event Management System</h2>
            <p>This is a platform where you can manage your events, RSVP to events, and keep track of your event details.</p>
            <p>To get started, please log in or sign up.</p>
        </div>

        <div class="auth-links">
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Event Management System. All rights reserved.</p>
    </footer>
</body>
</html>
