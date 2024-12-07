<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link rel="stylesheet" href="style.css"> <!-- External CSS file -->
</head>
<body>
    <header>
        <div class="header-left">
            <?php if (isset($_SESSION['username'])): ?>
                <p>Welcome, <?php echo $_SESSION['username']; ?></p>
            <?php else: ?>
                <p>Welcome, Guest</p>
            <?php endif; ?>
        </div>

        <div class="header-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Show Logout if user is logged in -->
                <!-- Event List and Add New Event for logged-in users -->
                <a href="list.php">Event List</a>
                <a href="add.php">Add New Event</a>
                <a href="statistics.php">Statistics</a>
                <a href="profile.php">Profile</a>
                <a href="notifications.php">Notifications</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <!-- Show Login and Signup links if user is not logged in -->
                <a href="login.php">Login</a> 
                <a href="signup.php">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <!-- Show navigation links for logged-in users only
    <!-php if (isset($_SESSION['user_id'])): ?> -->
        <!-- <nav>
            <ul>
                <li><a href="list.php">Event List</a></li>
                <li><a href="add.php">Add New Event</a></li>
            </ul>
        </nav> -->
    <!-- ?php endif; ?> --> 

</body>
</html>
