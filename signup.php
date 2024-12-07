<?php
require_once('db.php'); // Include DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use isset() to prevent the undefined index warning
    $username = $_POST['username'] ?? ''; // Default to empty string if not set
    $password = $_POST['password'] ?? '';
    $retype_password = $_POST['retype_password'] ?? '';
    $name = $_POST['name'] ?? '';  // Use isset() to check for the name field

    // Check if both passwords match
    if ($password !== $retype_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if the username already exists
        $stmt = $mysqli->prepare("SELECT * FROM users1 WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username already taken. Please choose a different one.";
        } else {
            // Hash the password before storing
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database including the name
            $stmt = $mysqli->prepare("INSERT INTO users1 (username, password, name) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashed_password, $name);

            if ($stmt->execute()) {
                header('Location: login.php'); // Redirect to login page after successful signup
                exit;
            } else {
                $error = "Error: Could not create user.";
            }
        }
    }
}
?>

<?php include('base.php'); ?>
<main>
    <!-- Error message display -->
    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Sign-Up Form -->
    <form id="signupForm" action="signup.php" method="POST">
        <h1 class="signup">Sign Up</h1>
        
        <!-- Name field -->
        <label for="name">Full Name:</label>
        <input type="text" name="name" id="name" required><br>

        <!-- Username field -->
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br>

        <!-- Password field -->
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required 
               minlength="6" title="Password should be at least 6 characters long."><br>

        <!-- Retype Password field -->
        <label for="retype_password">Retype Password:</label>
        <input type="password" name="retype_password" id="retype_password" required><br>

        <!-- Submit button -->
        <button type="submit">Sign Up</button>

        <!-- Login link -->
        <p>Already have an account? <a href="login.php">Log in</a></p>
    </form>

    <script>
        // Sign-Up Form Validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;
            var retypePassword = document.getElementById('retype_password').value;
            var name = document.getElementById('name').value;
            
            // Check if any fields are empty
            if (username.trim() === '' || password.trim() === '' || retypePassword.trim() === '' || name.trim() === '') {
                e.preventDefault();  // Prevent form submission
                alert('All fields are required!');
                return false;
            }

            // Check if passwords match
            if (password !== retypePassword) {
                e.preventDefault();  // Prevent form submission
                alert('Passwords do not match!');
                return false;
            }

            // Password length check
            if (password.length < 6) {
                e.preventDefault();  // Prevent form submission
                alert('Password must be at least 6 characters long!');
                return false;
            }

            return true;  // Submit the form if all validations pass
        });
    </script>
</main>


