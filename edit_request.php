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

// Fetch the existing team request details
$sql = "SELECT * FROM team_requests WHERE id = ? AND posted_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $request_id, $_SESSION['student_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No team request found or you are not authorized to edit this request.";
    exit();
}

$team_request = $result->fetch_assoc();

// Update the team request if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hackathon_name = $_POST['hackathon_name'];
    $team_description = $_POST['team_description'];
    $status = $_POST['status'];

    $update_sql = "UPDATE team_requests SET hackathon_name = ?, team_description = ?, status = ? WHERE id = ? AND posted_by = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssii", $hackathon_name, $team_description, $status, $request_id, $_SESSION['student_id']);

    if ($update_stmt->execute()) {
        header("Location: team_requests_status.php?msg=updated");
        exit();
    } else {
        echo "Error updating request: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Team Request</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f5f5f5;
    }
    form {
      max-width: 500px;
      margin: 20px auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    input, textarea, select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    button {
      background-color: #3b82f6;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #2563eb;
    }
  </style>
</head>
<body>
  <form method="POST">
    <h2>Edit Team Request</h2>
    <label for="hackathon_name">Hackathon Name</label>
    <input type="text" name="hackathon_name" id="hackathon_name" value="<?php echo htmlspecialchars($team_request['hackathon_name']); ?>" required>

    <label for="team_description">Team Description</label>
    <textarea name="team_description" id="team_description" rows="5" required><?php echo htmlspecialchars($team_request['team_description']); ?></textarea>

    <label for="status">Status</label>
    <select name="status" id="status">
      <option value="pending" <?php echo $team_request['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
      <option value="accepted" <?php echo $team_request['status'] == 'accepted' ? 'selected' : ''; ?>>Accepted</option>
      <option value="rejected" <?php echo $team_request['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
    </select>

    <button type="submit">Update Request</button>
  </form>
</body>
</html>
