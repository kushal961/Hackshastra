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

// Fetch the logged-in student's ID
$student_id = $_SESSION['student_id'];

// Fetch all pending team requests, joining with the students table to get the requester's name
$sql_team_requests = "SELECT tr.id, tr.hackathon_name, tr.team_roles, tr.skills_required, tr.num_members, tr.team_description, tr.status, tr.posted_by, 
                             s.name AS requester_name 
                      FROM team_requests tr 
                      JOIN students s ON tr.posted_by = s.id 
                      WHERE tr.status = 'pending' AND tr.posted_by != ? 
                      ORDER BY tr.created_at DESC";
$stmt_team_requests = $conn->prepare($sql_team_requests);
$stmt_team_requests->bind_param("i", $student_id);
$stmt_team_requests->execute();
$team_requests_result = $stmt_team_requests->get_result();

// Handle Accept/Reject actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accept_request'])) {
        $request_id = $_POST['request_id'];

        // Update the team_requests table status to 'accepted'
        $update_sql = "UPDATE team_requests SET status = 'accepted' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $request_id);
        $update_stmt->execute();

        // Insert the accepting student's data into the team_acceptances table
        $insert_acceptance_sql = "INSERT INTO team_acceptances (team_request_id, student_id, status) VALUES (?, ?, 'Accepted')";
        $insert_acceptance_stmt = $conn->prepare($insert_acceptance_sql);
        $insert_acceptance_stmt->bind_param("ii", $request_id, $student_id);
        $insert_acceptance_stmt->execute();

        // Redirect to refresh the page
        header("Location: available_requests.php");
        exit();
    } elseif (isset($_POST['reject_request'])) {
        $request_id = $_POST['request_id'];

        // Update the team_requests table status to 'rejected'
        $update_sql = "UPDATE team_requests SET status = 'rejected' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $request_id);
        $update_stmt->execute();

        // Redirect to refresh the page
        header("Location: available_requests.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Team Requests</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f9;
      margin: 0;
      padding: 0;
    }

    .header {
      background-color: #2e3a59;
      color: #fff;
      padding: 30px 0;
      text-align: center;
    }

    .header h1 {
      font-size: 36px;
      margin: 0;
    }

    .requests-main {
      display: flex;
      justify-content: center;
      padding: 30px 10px;
    }

    .requests-container {
      width: 80%;
      max-width: 1000px;
    }

    .section-title {
      font-size: 28px;
      margin-bottom: 20px;
    }

    .request-card {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      transition: transform 0.3s ease;
    }

    .request-card:hover {
      transform: scale(1.02);
    }

    .request-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .status {
      padding: 5px 15px;
      border-radius: 20px;
      background-color: #ffcc00;
      font-weight: bold;
    }

    .status.accepted {
      background-color: #4CAF50;
      color: white;
    }

    .status.rejected {
      background-color: #f44336;
      color: white;
    }

    .request-footer {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .btn {
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      color: white;
      font-weight: bold;
    }

    .btn-accept {
      background-color: #4CAF50;
    }

    .btn-reject {
      background-color: #f44336;
    }

    .footer {
      background-color: #2e3a59;
      color: #fff;
      padding: 20px 0;
      text-align: center;
    }

    .social-icons a {
      margin: 0 15px;
      color: #fff;
      text-decoration: none;
      font-size: 18px;
    }

    .social-icons a:hover {
      opacity: 0.7;
    }
  </style>
</head>
<body>
  <header class="header">
    <h1>Available Team Requests for Hackathons</h1>
    <p>Choose a team and join your desired hackathon!</p>
  </header>

  <main class="requests-main">
    <div class="requests-container">
      <h2 class="section-title">Browse Available Team Requests</h2>

      <?php if ($team_requests_result->num_rows > 0): ?>
        <?php while ($team_request = $team_requests_result->fetch_assoc()): ?>
          <div class="request-card">
            <div class="request-header">
              <h3><?php echo htmlspecialchars($team_request['hackathon_name']); ?></h3>
              <span class="status <?php echo strtolower($team_request['status']); ?>"><?php echo ucfirst($team_request['status']); ?></span>
            </div>

            <p><strong>Posted By:</strong> <?php echo htmlspecialchars($team_request['requester_name']); ?></p>
            <p><strong>Roles Needed:</strong> <?php echo htmlspecialchars($team_request['team_roles']); ?></p>
            <p><strong>Required Skills:</strong> <?php echo htmlspecialchars($team_request['skills_required']); ?></p>
            <p><strong>Number of Members:</strong> <?php echo htmlspecialchars($team_request['num_members']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($team_request['team_description']); ?></p>

            <div class="request-footer">
                <form method="POST" action="available_requests.php">
                    <input type="hidden" name="request_id" value="<?php echo $team_request['id']; ?>">
                    <button type="submit" name="accept_request" class="btn btn-accept">Accept</button>
                    <button type="submit" name="reject_request" class="btn btn-reject">Reject</button>
                </form>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No available team requests at the moment. Please check back later.</p>
      <?php endif; ?>

    </div>
  </main>

  <footer class="footer">
    <p>&copy; 2024 Hackshastra. All rights reserved.</p>
    <div class="social-icons">
      <a href="#" target="_blank">💼 LinkedIn</a>
      <a href="#" target="_blank">🐦 Twitter</a>
      <a href="#" target="_blank">🚀 GitHub</a>
    </div>
  </footer>

</body>
</html>


