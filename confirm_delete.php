<?php
session_start();
require_once('db.php'); // Include DB connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Check if the event ID is passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch event details to display in the confirmation message
    $stmt = $mysqli->prepare("SELECT event_name FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start a transaction to ensure both deletions happen together
    $mysqli->begin_transaction();

    try {
        // First, delete the associated RSVP records
        $stmt_rsvp = $mysqli->prepare("DELETE FROM rsvps WHERE event_id = ?");
        $stmt_rsvp->bind_param("i", $id);
        $stmt_rsvp->execute();

        // Then, delete the event itself
        $stmt_event = $mysqli->prepare("DELETE FROM events WHERE id = ?");
        $stmt_event->bind_param("i", $id);
        $stmt_event->execute();

        // Commit the transaction
        $mysqli->commit();

        // Redirect to the event list page after deletion
        header('Location: list.php');
        exit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $mysqli->rollback();
        echo "Error occurred while deleting the event. Please try again.";
        exit();
    }
}

include('base.php');
?>
<main>
    

    <?php if (isset($event)): ?>
        <form action="confirm_delete.php?id=<?php echo $id; ?>" method="POST">
            <h1 id="delete-text">Delete Event</h1>
            <p>Are you sure you want to delete the event "<strong><?php echo htmlspecialchars($event['event_name']); ?></strong>"?</p>
            <button id="delete-resv" type="submit" name="confirm_delete">Yes, delete this event</button>
            <a id="cancel-link" href="list.php">Cancel</a>
        </form>
    <?php else: ?>
        <p>Event not found.</p>
    <?php endif; ?>
</main>
