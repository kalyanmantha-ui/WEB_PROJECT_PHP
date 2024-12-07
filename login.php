<?php
session_start();
require_once('db.php'); // Include DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $stmt = $mysqli->prepare("SELECT id, password FROM users1 WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // If password matches, set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;

        header('Location: list.php'); // Redirect to event list page
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<?php include('base.php'); ?>
<main>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
    <h1 class="login">Login</h1>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Login</button>

        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
    </form>
</main>
