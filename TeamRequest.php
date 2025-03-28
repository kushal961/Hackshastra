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
    $sql = "INSERT INTO team_requests (student_id, hackathon_name, team_roles, num_members, skills_required, team_description)
            VALUES ('$student_id', '$hackathon_name', '$team_roles', '$num_members', '$skills_required', '$team_description')";

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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      font-family: 'Poppins', sans-serif;
      color: #fff;
    }

    .container {
      background: #fff;
      padding: 40px;
      border-radius: 10px;
      margin-top: 50px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      color: #333;
    }

    .header {
      text-align: center;
      margin-bottom: 30px;
    }

    .header h2 {
      font-weight: 600;
      color: #2575fc;
    }

    .header p {
      color: #6c757d;
      font-size: 14px;
    }

    .form-group label {
      font-weight: 500;
      color: #6c757d;
    }

    .form-control {
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
      background: #6a11cb;
      border: none;
      border-radius: 50px;
      padding: 10px 30px;
      font-weight: 500;
      text-transform: uppercase;
      transition: background 0.3s;
    }

    .btn-primary:hover {
      background: #2575fc;
    }

    .alert {
      border-radius: 5px;
    }

    .form-icon {
      position: absolute;
      margin-left: -30px;
      margin-top: 10px;
      color: #6c757d;
    }

    .form-group {
      position: relative;
    }

    footer {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
      color: #ddd;
    }

    footer a {
      color: #fff;
      text-decoration: none;
      font-weight: bold;
    }

    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h2>Create a Team Request</h2>
      <p>Fill out the form below to find the right teammates for your hackathon project.</p>
    </div>

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
        <i class="fas fa-laptop-code form-icon"></i>
        <input type="text" id="hackathonName" name="hackathonName" class="form-control" placeholder="Enter Hackathon Name" required>
      </div>
      <div class="form-group">
        <label for="teamRoles">Team Roles:</label>
        <i class="fas fa-users form-icon"></i>
        <input type="text" id="teamRoles" name="teamRoles" class="form-control" placeholder="e.g., Frontend Developer, Designer" required>
      </div>
      <div class="form-group">
        <label for="numMembers">Number of Members:</label>
        <i class="fas fa-user-plus form-icon"></i>
        <input type="number" id="numMembers" name="numMembers" class="form-control" placeholder="Enter number of members required" required>
      </div>
      <div class="form-group">
        <label for="skillsRequired">Skills Required:</label>
        <i class="fas fa-tools form-icon"></i>
        <input type="text" id="skillsRequired" name="skillsRequired" class="form-control" placeholder="e.g., React, Node.js">
      </div>
      <div class="form-group">
        <label for="teamDescription">Team Description:</label>
        <i class="fas fa-pencil-alt form-icon"></i>
        <textarea id="teamDescription" name="teamDescription" class="form-control" rows="4" placeholder="Write a brief description of the team project or skills needed"></textarea>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Create Request</button>
    </form>
  </div>

  <footer style="height:50px; border:2px solid white; background-color:white; border-radius: 5px; color:black;">
    © Hackshastra 2024 - Made with ❤️ by Your Team 
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>


