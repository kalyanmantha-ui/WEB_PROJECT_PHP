<?php
session_start();
require_once('db.php'); // Include DB connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch events created by the user
$stmt = $mysqli->prepare("SELECT * FROM events WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$events_result = $stmt->get_result();

// Store statistics
$statistics = [];

while ($event = $events_result->fetch_assoc()) {
    $event_id = $event['id'];

    // Get the number of RSVPs for the event
    $rsvp_stmt = $mysqli->prepare("SELECT COUNT(*) FROM rsvps WHERE event_id = ?");
    $rsvp_stmt->bind_param("i", $event_id);
    $rsvp_stmt->execute();
    $rsvp_result = $rsvp_stmt->get_result();
    $rsvp_count = $rsvp_result->fetch_row()[0];

    // Get the guest list for the event
    $guest_stmt = $mysqli->prepare("SELECT name FROM rsvps WHERE event_id = ?");
    $guest_stmt->bind_param("i", $event_id);
    $guest_stmt->execute();
    $guest_result = $guest_stmt->get_result();
    $guest_list = [];
    while ($guest = $guest_result->fetch_assoc()) {
        $guest_list[] = $guest['name'];
    }

    // Store the event's statistics
    $statistics[] = [
        'event_name' => $event['event_name'],
        'rsvp_count' => $rsvp_count,
        'guest_list' => $guest_list
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Event Statistics</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Your Event Statistics</h1>
        <div class="header-right">
            <a href="list.php">Event List</a>
            <a href="add.php">Add New Event</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <?php if (empty($statistics)): ?>
            <p>You haven't created any events yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>RSVP Count</th>
                        <th>Guest List</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statistics as $stat): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($stat['event_name']); ?></td>
                            <td><?php echo $stat['rsvp_count']; ?></td>
                            <td>
                                <ul>
                                    <?php foreach ($stat['guest_list'] as $guest): ?>
                                        <li><?php echo htmlspecialchars($guest ?: 'No name provided'); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
