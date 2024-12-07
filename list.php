<?php
session_start();
require_once('db.php'); // Include DB connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch events from the database
$sql = "SELECT * FROM events";
$result = $mysqli->query($sql);

include('base.php'); // Include header and navigation
?>
<main>
    <h1 class="event-list-title">Event List</h1>
    <table>
        <tr>
            <th>Event Name</th>
            <th>Date</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($event = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $event['event_name']; ?></td>
            <td><?php echo $event['event_date']; ?></td>
            <td><?php echo $event['description']; ?></td>
            <td>
                <a href="event_details.php?id=<?php echo $event['id']; ?>">View</a>
                <?php if ($event['user_id'] == $_SESSION['user_id']): ?>
                <a href="edit.php?id=<?php echo $event['id']; ?>">Edit</a>
                <a href="confirm_delete.php?id=<?php echo $event['id']; ?>">Delete</a>
                
                <?php else: ?>
                        <!-- Trigger a JavaScript pop-up if the user doesn't have permission -->
                        <a href="#" onclick="showPermissionPopup()">Edit</a>
                        <a href="#" onclick="showPermissionPopup()">Delete</a>
                    <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

     <!-- Modal for permission -->
     <div id="permissionPopup" style="display: none;">
        <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
            <div style="background-color: white; width: 300px; padding: 20px; margin: 100px auto; text-align: center; border-radius: 10px;">
                <h2>You don't have permission</h2>
                <p>You are not the owner of this event, so you cannot edit or delete it.</p>
                <button onclick="closePermissionPopup()">Close</button>
            </div>
        </div>
    </div>

</main>


<script>
    // Function to display the permission pop-up
    function showPermissionPopup() {
        document.getElementById('permissionPopup').style.display = 'block';
    }

    // Function to close the permission pop-up
    function closePermissionPopup() {
        document.getElementById('permissionPopup').style.display = 'none';
    }
</script>
