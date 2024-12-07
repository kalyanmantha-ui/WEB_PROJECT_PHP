<?php
session_start();
require_once('db.php'); // Include DB connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's RSVP data
$stmt_rsvps = $mysqli->prepare("SELECT e.event_name, e.event_date, e.description FROM rsvps r
                                JOIN events e ON r.event_id = e.id
                                WHERE r.user_id = ?");
$stmt_rsvps->bind_param("i", $user_id);
$stmt_rsvps->execute();
$rsvps_result = $stmt_rsvps->get_result();

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the new password matches the confirmation
    if ($new_password !== $confirm_password) {
        $error_message = "New passwords do not match!";
    } else {
        // Fetch the current password from the database
        $stmt = $mysqli->prepare("SELECT password FROM users1 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verify the current password
        if (!password_verify($current_password, $hashed_password)) {
            $error_message = "Current password is incorrect.";
        } else {
            // Update the password in the database
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $mysqli->prepare("UPDATE users1 SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $new_hashed_password, $user_id);
            $update_stmt->execute();
            $success_message = "Password updated successfully!";
        }
    }
}

include('base.php'); // Include header and navigation
?>

<main>
    <h1>Profile Page</h1>

    <!-- RSVP Section -->
    <h2>Your RSVPs</h2>
    <?php if ($rsvps_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
            <?php while ($rsvp = $rsvps_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rsvp['event_name']); ?></td>
                    <td><?php echo htmlspecialchars($rsvp['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($rsvp['description']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You haven't RSVP'd for any events yet.</p>
    <?php endif; ?>

    <!-- Password Change Form -->
    
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <form action="profile.php" method="POST">
        <h2 id="change-pw">Change Password</h2>
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" id="current_password" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br>

        <button type="submit" name="change_password">Change Password</button>
    </form>
</main>
