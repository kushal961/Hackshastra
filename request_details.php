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

// Check if a request ID is provided via the URL
if (isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // SQL query to retrieve the team request and the students who accepted it
    $sql = "
        SELECT 
            tr.hackathon_name,
            tr.team_description,
            tr.num_members,
            ta.student_id AS accepted_student_id,
            s.name AS accepted_student_name,
            s.email AS accepted_student_email,
            s.github AS accepted_student_github
        FROM 
            team_requests tr
        LEFT JOIN 
            team_acceptances ta ON tr.id = ta.request_id AND ta.status = 'accepted'
        LEFT JOIN 
            students s ON ta.student_id = s.id
        WHERE 
            tr.id = ? AND tr.posted_by = ?
    ";

    // Prepare and bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $request_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If the request does not exist or the user is not the poster
    if ($result->num_rows === 0) {
        echo "Request not found or you are not the poster of this request.";
        exit();
    }

    // Fetch request data
    $request_data = $result->fetch_assoc();
} else {
    echo "No request ID provided.";
    exit();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Details</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <h1>Request Details</h1>
    <p>Details for the request you posted.</p>
  </header>

  <main>
    <h2>Request Information</h2>
    <p><strong>Competition Name:</strong> <?php echo htmlspecialchars($request_data['hackathon_name']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($request_data['team_description']); ?></p>
    <p><strong>Members Required:</strong> <?php echo htmlspecialchars($request_data['num_members']); ?></p>

    <h3>Accepted Students:</h3>
    <?php if ($result->num_rows > 0): ?>
      <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
          <li>
            <strong>Name:</strong> <?php echo htmlspecialchars($row['accepted_student_name']); ?><br>
            <strong>Email:</strong> <?php echo htmlspecialchars($row['accepted_student_email']); ?><br>
            <strong>GitHub:</strong> <a href="<?php echo htmlspecialchars($row['accepted_student_github']); ?>" target="_blank">View Profile</a>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No students have accepted your request yet.</p>
    <?php endif; ?>
  </main>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
