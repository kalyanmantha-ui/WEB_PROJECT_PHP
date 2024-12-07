<?php
session_start();
require_once('db.php'); // Include DB connection file

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$id = $_GET['id'];
$stmt = $mysqli->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $description = $_POST['description'];

    // Update event in database
    $stmt = $mysqli->prepare("UPDATE events SET event_name = ?, event_date = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sssi", $event_name, $event_date, $description, $id);
    $stmt->execute();
    header('Location: list.php');
    exit;
}
?>
<?php include('base.php'); ?> 
<main>
    
    <form action="edit.php?id=<?php echo $event['id']; ?>" method="POST">
    <h1 class="event-list-title edit">Edit Event</h1>
        <div class="edit-event">
            <label for="event_name">Event Name:</label>
            <input type="text" name="event_name" id="event_name" value="<?php echo $event['event_name']; ?>" required><br>
        </div>
        <div class="edit-event">
            <label for="event_date">Event Date:</label>
            <input type="datetime-local" name="event_date" id="event_date" value="<?php echo $event['event_date']; ?>" required><br>
        </div>
        <div class="edit-event">
            <label for="description">Description:</label>
            <textarea name="description" id="description"><?php echo $event['description']; ?></textarea><br>
        </div>
        <button type="submit">Update Event</button>
    </form>
</main>
