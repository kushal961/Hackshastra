<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM students WHERE email=?");
    $stmt->bind_param("s", $email); // bind the email to prevent SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password with the stored hashed password
        if (password_verify($password, $row['password'])) {
            // Set session variables upon successful login
            $_SESSION['student_id'] = $row['id']; // Ensure you are using the correct column name for student ID
            $_SESSION['name'] = $row['name']; // Save the student name for use on the dashboard
            header("Location: dashboard.php"); // Redirect to dashboard page
            exit();
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "No user found with that email address!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Hackshastra</title>
    <link rel="stylesheet" href="styles8.css">
</head>
<body>
    <div class="login-container">
        <div class="form-section">
            <h1>Hackshastra Login</h1>
            <form method="POST" action="">
                <!-- Display error messages -->
                <?php if (isset($error_message)) { ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php } ?>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn">Login</button>
                <p class="register-link">Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>

        <div class="illustration-section">
            <img src="log (1).png" alt="Login Illustration">
        </div>
    </div>
</body>
</html>
