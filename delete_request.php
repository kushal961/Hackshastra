<?php
// Start the session
session_start();

// Include the database connection file
include('db.php');

// Redirect to login if not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the team request ID from the URL
$request_id = $_GET['id'] ?? null;

// Redirect back if no ID is provided
if (!$request_id) {
    header("Location: team_requests_status.php");
    exit();
}

// Delete the team request if it belongs to the logged-in user
$sql = "DELETE FROM team_requests WHERE id = ? AND posted_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $request_id, $_SESSION['student_id']);

if ($stmt->execute()) {
    header("Location: team_requests_status.php?msg=deleted");
    exit();
} else {
    echo "Error deleting request: " . $conn->error;
}
?>
