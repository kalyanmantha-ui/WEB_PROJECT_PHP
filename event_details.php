<?php
// event-details.php

session_start();
require_once('db.php'); // Include DB connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Check if event ID is passed in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = $_GET['id']; // Get the event ID from the URL

    // Fetch event details from the database
    $stmt = $mysqli->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if event is found
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc(); // Fetch the event details
    } else {
        echo "No event found with ID $event_id.";
        exit;
    }

    // Check if the user has already RSVP'd for this event
    $rsvp_check_stmt = $mysqli->prepare("SELECT * FROM rsvps WHERE event_id = ? AND user_id = ?");
    $rsvp_check_stmt->bind_param("ii", $event_id, $user_id);
    $rsvp_check_stmt->execute();
    $rsvp_check_result = $rsvp_check_stmt->get_result();
    $has_rsvped = $rsvp_check_result->num_rows > 0;
} else {
    echo "Invalid event ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Event Details</h1>
        <div class="header-right">
            <a href="list.php">Event List</a>
            <a href="add.php">Add New Event</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <div class="event-details">
            
            <!-- If the user has already RSVP'd -->
            <?php if ($has_rsvped): ?>
                <p style="color: red; font-weight: bold;">You have already RSVP'd for this event.</p>
            <?php endif; ?>

            <!-- Event Information -->
            <div class="event-info">
                <h2><?php echo htmlspecialchars($event['event_name']); ?></h2>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($event['event_date']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($event['description']); ?></p>
            </div>

            <!-- RSVP Section -->
            <?php if (!$has_rsvped): ?>
                <h3>RSVP for Event</h3>
                <form action="rsvp.php" method="POST">
                    <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">
                    <!-- Automatically fetch the user's name from the session -->
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>"> 
                    <button type="submit">RSVP</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
