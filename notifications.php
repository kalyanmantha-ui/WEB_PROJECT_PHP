<?php
session_start();
require_once('db.php'); // Include DB connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch the notifications for the logged-in user
$user_id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY timestamp DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Mark notifications as read when viewed
$update_stmt = $mysqli->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
$update_stmt->bind_param("i", $user_id);
$update_stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Notifications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Notifications</h1>
        <div class="header-right">
            <a href="list.php">Event List</a>
            <a href="add.php">Add New Event</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <div class="notifications">
            <h2>Your Notifications</h2>
            <?php if ($result->num_rows > 0): ?>
                <ul>
                    <?php while ($notification = $result->fetch_assoc()): ?>
                        <li class="<?php echo $notification['is_read'] ? 'read' : 'unread'; ?>">
                            <p><strong>Event:</strong> <?php echo $notification['event_id'] ? 'Event #' . $notification['event_id'] : 'General'; ?></p>
                            <p><?php echo htmlspecialchars($notification['message']); ?></p>
                            <p><em>Received: <?php echo $notification['timestamp']; ?></em></p>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No notifications yet.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
