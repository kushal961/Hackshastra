<?php
// Database credentials
$host = 'localhost'; // Database host
$username = 'root'; // Database username
$password = ''; // Database password (default is empty for XAMPP)
$database = 'hacksh1'; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    // Output a user-friendly error message without revealing sensitive details
    die("Connection failed: Unable to connect to the database.");
}

// Optionally, set the character set to UTF-8 to ensure proper encoding (e.g., for special characters)
$conn->set_charset("utf8");
?>

