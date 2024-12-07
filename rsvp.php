<?php
session_start();
require_once('db.php'); // Include DB connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Check if the event ID is provided and is numeric
if (isset($_POST['event_id']) && is_numeric($_POST['event_id'])) {
    $event_id = $_POST['event_id']; // Get the event ID
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Fetch the user's name from the database
    $name_stmt = $mysqli->prepare("SELECT name FROM users1 WHERE id = ?");
    $name_stmt->bind_param("i", $user_id);
    $name_stmt->execute();
    $name_result = $name_stmt->get_result();
    
    if ($name_result->num_rows > 0) {
        $user = $name_result->fetch_assoc();
        $name = $user['name']; // Get the name of the logged-in user
    } else {
        // If the user name is not found, display an error and exit
        echo "<script>alert('User name not found.'); window.location.href = 'list.php';</script>";
        exit;
    }

    // Check if the user has already RSVPed for this event
    $check_rsvp = $mysqli->prepare("SELECT * FROM rsvps WHERE user_id = ? AND event_id = ?");
    $check_rsvp->bind_param("ii", $user_id, $event_id);
    $check_rsvp->execute();
    $rsvp_result = $check_rsvp->get_result();

    if ($rsvp_result->num_rows > 0) {
        // User has already RSVPed
        echo "<script>alert('You have already RSVP\'d for this event.'); window.location.href = 'event_details.php?id=$event_id';</script>";
        exit;
    }

    // Insert the RSVP into the rsvps table
    $stmt = $mysqli->prepare("INSERT INTO rsvps (user_id, event_id, name) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $event_id, $name);
    $stmt->execute();

    // Fetch event details
    $event_stmt = $mysqli->prepare("SELECT event_name FROM events WHERE id = ?");
    $event_stmt->bind_param("i", $event_id);
    $event_stmt->execute();
    $event_result = $event_stmt->get_result();
    $event = $event_result->fetch_assoc();

    // Create a notification for the user
    $message = "You have successfully RSVP'd for the event: " . $event['event_name'];
    $notification_stmt = $mysqli->prepare("INSERT INTO notifications (user_id, message, event_id) VALUES (?, ?, ?)");
    $notification_stmt->bind_param("isi", $user_id, $message, $event_id);
    $notification_stmt->execute();

    // Redirect back to the event details page with a success message
    echo "<script>alert('You have successfully RSVP\'d for the event.'); window.location.href = 'event_details.php?id=$event_id';</script>";
    exit;
} else {
    // Invalid event ID or missing POST data
    echo "<script>alert('Invalid event ID.'); window.location.href = 'list.php';</script>";
    exit;
}
?>
