<?php
// Railway automatically fills these getenv() values securely behind the scenes
$host     = getenv('MYSQLHOST') ?: 'roundhouse.proxy.rlwy.net'; 
$username = getenv('MYSQLUSER') ?: 'root'; 
$password = getenv('MYSQLPASSWORD') ?: 'McScSfaPFZOhUxwQlTXliSQLefbwFczg'; 
$database = getenv('MYSQLDATABASE') ?: 'railway'; 
$port     = getenv('MYSQLPORT') ?: 3306; 

// Establish connection passing the correct network port
$conn = new mysqli($host, $username, $password, $database, $port);

// Check the connection
if ($conn->connect_error) {
    // If it fails, print the precise error to tell us exactly what's wrong
    die("Connection failed: " . $conn->connect_error);
}

// Ensure proper character encoding for user forms
$conn->set_charset("utf8mb4");
?>
