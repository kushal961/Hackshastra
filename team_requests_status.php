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

// SQL query to retrieve team requests posted by the logged-in student
$sql_team_requests = "
    SELECT tr.*, 
           s.name AS student_name, 
           s.email AS student_email, 
           s.github AS student_github,
           ta.student_id AS accepted_student_id, 
           ta.status AS accepted_status,
           a.name AS accepted_name, 
           a.email AS accepted_email, 
           a.github AS accepted_github
    FROM team_requests tr
    LEFT JOIN team_acceptances ta ON tr.id = ta.team_request_id AND ta.status = 'Accepted'
    LEFT JOIN students s ON tr.posted_by = s.id
    LEFT JOIN students a ON ta.student_id = a.id
    WHERE tr.posted_by = ?
    ORDER BY tr.created_at DESC";

$stmt_team_requests = $conn->prepare($sql_team_requests);
$stmt_team_requests->bind_param("i", $student_id);
$stmt_team_requests->execute();
$team_requests_result = $stmt_team_requests->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Team Requests</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
    }

    header {
      background-color: #3b82f6;
      color: white;
      padding: 20px;
      text-align: center;
    }

    main {
      max-width: 1200px;
      margin: 20px auto;
      padding: 10px;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 20px;
      position: relative;
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .status-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      padding: 5px 10px;
      border-radius: 20px;
      color: white;
      font-size: 12px;
      font-weight: bold;
    }

    .status-pending {
      background-color: #facc15;
    }

    .status-accepted {
      background-color: #16a34a;
    }

    .status-rejected {
      background-color: #dc2626;
    }

    .action-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }

    .action-buttons a {
      text-decoration: none;
      font-size: 14px;
      padding: 10px 15px;
      border-radius: 5px;
      text-align: center;
      display: inline-block;
      color: white;
      transition: background-color 0.3s;
    }

    .btn-edit {
      background-color: #3b82f6;
    }

    .btn-edit:hover {
      background-color: #2563eb;
    }

    .btn-delete {
      background-color: #dc2626;
    }

    .btn-delete:hover {
      background-color: #b91c1c;
    }

    .fallback-message {
      text-align: center;
      font-size: 16px;
      color: #6b7280;
    }
  </style>
  <script>
    function confirmDelete(requestId) {
      if (confirm("Are you sure you want to delete this team request?")) {
        window.location.href = `delete_request.php?id=${requestId}`;
      }
    }
  </script>
</head>
<body>
  <header>
    <h1>Your Team Requests</h1>
    <p>Manage your team requests with ease.</p>
  </header>
  <main>
    <?php if ($team_requests_result->num_rows > 0): ?>
      <div class="grid-container">
        <?php while ($team_request = $team_requests_result->fetch_assoc()): ?>
          <div class="card">
            <span class="status-badge status-<?php echo htmlspecialchars(strtolower($team_request['status'])); ?>">
              <?php echo htmlspecialchars(ucfirst($team_request['status'])); ?>
            </span>
            <p><strong>Competition:</strong> <?php echo htmlspecialchars($team_request['hackathon_name']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($team_request['team_description']); ?></p>
            <p><strong>Members Required:</strong> <?php echo $team_request['num_members']; ?></p>
            
            <?php if (!empty($team_request['accepted_student_id'])): ?>
              <p><strong>Accepted by:</strong> <?php echo htmlspecialchars($team_request['accepted_name']); ?></p>
              <p><strong>Email:</strong> <?php echo htmlspecialchars($team_request['accepted_email']); ?></p>
              <p><strong>GitHub:</strong> 
                <a href="<?php echo htmlspecialchars($team_request['accepted_github']); ?>" target="_blank">View Profile</a>
              </p>
            <?php else: ?>
              <p><strong>Status:</strong> No one has accepted your team request yet.</p>
            <?php endif; ?>

            <div class="action-buttons">
              <a href="edit_request.php?id=<?php echo $team_request['id']; ?>" class="btn-edit">Edit</a>
              <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $team_request['id']; ?>)" class="btn-delete">Delete</a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="fallback-message">You haven't posted any team requests yet.</p>
    <?php endif; ?>
  </main>
</body>
</html>





