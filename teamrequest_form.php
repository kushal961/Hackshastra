<?php
// Start the session
session_start();

// Include the database connection file
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Get the student ID from the session
$student_id = $_SESSION['student_id'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and get form data
    $hackathon_name = mysqli_real_escape_string($conn, $_POST['hackathonName']);
    $team_roles = mysqli_real_escape_string($conn, $_POST['teamRoles']);
    $num_members = $_POST['numMembers'];
    $skills_required = mysqli_real_escape_string($conn, $_POST['skillsRequired']);
    $team_description = mysqli_real_escape_string($conn, $_POST['teamDescription']);

    // Insert team request into the database
    $sql = "INSERT INTO team_requests (student_id, hackathon_name, team_roles, num_members, skills_required, team_description, posted_by)
            VALUES ('$student_id', '$hackathon_name', '$team_roles', '$num_members', '$skills_required', '$team_description', '$student_id')";

    if (mysqli_query($conn, $sql)) {
        // Redirect to the dashboard or success page
        header("Location: dashboard.php?success=Team Request Created Successfully");
        exit();
    } else {
        // Display error message if the insertion failed
        $error_message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Team Request</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles5.css">
  <style>
    body {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      color: white;
      font-family: 'Arial', sans-serif;
    }

    .container {
      background-color: rgba(255, 255, 255, 0.9);
      color: #333;
      border-radius: 10px;
      padding: 30px;
      margin-top: 50px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    h2 {
      color: #6a11cb;
      font-weight: bold;
    }

    .btn-primary {
      background-color: #2575fc;
      border: none;
    }

    .btn-primary:hover {
      background-color: #6a11cb;
    }

    .form-group label {
      font-weight: bold;
    }

    .alert {
      border-radius: 5px;
    }

    footer {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #ddd;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2 class="text-center mb-4">Create a Team Request</h2>
    
    <!-- Display success/error message -->
    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
        </div>
    <?php } ?>
    <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php } ?>

    <form action="teamrequest_form.php" method="POST">

      <div class="form-group">
        <label for="hackathonName">Hackathon Name:</label>
        <input type="text" id="hackathonName" name="hackathonName" class="form-control" placeholder="Enter Hackathon Name" required>
      </div>

      <div class="form-group">
        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your Name" required>
      </div>

      <div class="form-group">
        <label for="teamRoles">Team Roles (e.g., Frontend Developer, Designer):</label>
        <input type="text" id="teamRoles" name="teamRoles" class="form-control" placeholder="Enter required team roles" required>
      </div>
      
      <div class="form-group">
        <label for="numMembers">Number of Members:</label>
        <input type="number" id="numMembers" name="numMembers" class="form-control" placeholder="Enter number of members required" required>
      </div>
      
      <div class="form-group">
        <label for="skillsRequired">Skills Required:</label>
        <input type="text" id="skillsRequired" name="skillsRequired" class="form-control" placeholder="Enter skills needed (e.g., React, Node.js)">
      </div>
      
      <div class="form-group">
        <label for="teamDescription">Team Description:</label>
        <textarea id="teamDescription" name="teamDescription" class="form-control" rows="4" placeholder="Write a brief description of the team project or skills needed"></textarea>
      </div>

      <button type="submit" class="btn btn-primary btn-block">Create Request</button>
    </form>
  </div>

  <footer>
    © Hackshastra 2024 - All rights reserved.
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>

