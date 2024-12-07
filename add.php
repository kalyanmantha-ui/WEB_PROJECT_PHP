<?php
session_start();
require_once('db.php'); // Include DB connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get event details from POST data
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

    // Prepare and execute the query to insert the event into the database
    $stmt = $mysqli->prepare("INSERT INTO events (event_name, event_date, description, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $event_name, $event_date, $description, $user_id); // Bind parameters
    if ($stmt->execute()) {
        // Redirect to the event list after adding the event
        header('Location: list.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

include('base.php');
?>

<!-- HTML Form for Adding Event -->
<main>
    <form action="add.php" method="POST">
        <h1 class="add">Add New Event</h1>
        <label for="event_name">Event Name:</label>
        <input type="text" name="event_name" id="event_name" required><br>

        <label for="event_date">Event Date:</label>
        <input type="datetime-local" name="event_date" id="event_date" required><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea><br>

        <button type="submit">Add Event</button>
    </form>
</main>
