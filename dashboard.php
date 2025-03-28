<?php
session_start();
include('db.php');

// Check if the user is logged in by verifying session data
if (!isset($_SESSION['student_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Fetch student details from the database using session student ID
$student_id = $_SESSION['student_id'];
$query = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id); // bind the student ID to the query
$stmt->execute();
$result = $stmt->get_result();

// Check if a student record was found
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    // Store student details in variables
    $name = $student['name'];
    $student_skills = $student['skills'];
    $student_interests = $student['interests'];
    $student_branch = $student['branch'];
    $student_year = $student['year'];
    $student_github = $student['github'];
} else {
    // If no student data found, redirect to login page
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles9.css">
</head>
<body>
  <!-- Header Section -->
  <header class="dashboard-header">
    <h1 id="wc">Welcome, <?php echo htmlspecialchars($name); ?> at HACKSHASTRA! 👋</h1>
    <p id="wc2">Your space for collaboration, learning, and updates.</p>
  </header>

  <!-- Main Content -->
  <main class="dashboard-main">
    <section class="left-column">
      <!-- Profile Summary Section -->
      <div class="section profile-summary">
      <h2>Your Profile 🌟</h2>
<div class="profile-card">
  <div class="profile-content">
    <!-- Left: User Data -->
    <div class="profile-info">
      <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
      <p><strong>Skills:</strong> <?php echo htmlspecialchars($student_skills); ?></p>
      <p><strong>Interests:</strong> <?php echo htmlspecialchars($student_interests); ?></p>
      <p><strong>Branch:</strong> <?php echo htmlspecialchars($student_branch); ?></p>
      <p><strong>Year:</strong> <?php echo htmlspecialchars($student_year); ?></p>
      <a href="<?php echo htmlspecialchars($student_github); ?>" target="_blank">View GitHub</a>
    </div>
    <!-- Right: Profile Image -->
    <div class="profile-image">
      <img src="laptop-computer.png" alt="User Profile" />
    </div>
  </div>
  <a href="logout.php" class="logout-btn">Logout</a>
</div>

      <div class="section mentorship-networking"  style="border:2px solid #e3dce5e3;">
        <h2>Mentorship & Networking 🤝</h2>
        <p>Find mentors and connect with peers to boost your professional growth!</p>

        <h3>Upcoming Networking Events:</h3>
        <ul>
            <li>
                <h4>Global Tech Conference 2024</h4>
                <p>Join industry professionals at the largest global tech conference.</p>
                <a href="https://www.globaltechconference.com" class="action-btn" target="_blank">Register Now</a>
            </li>
            <li>
                <h4>Virtual Hackathon Networking Event</h4>
                <p>Meet fellow developers and industry experts virtually.</p>
                <a href="https://www.hackathon-networking.com" class="action-btn" target="_blank">Join the Event</a>
            </li>
        </ul>
      </div>


      <section class="team-actions">
      <!-- Team Formation Actions Section -->
      <div class="section team-actions" style=" border: 3px solid #afcdee;">
        <h2>Team Formation Actions</h2>
        <div class="action-container">
          <a href="TeamRequest.php" class="action-btn">Post Team Request</a>
          <a href="team_requests_status.php" class="action-btn">View Your Team Requests</a>
          <a href="available_requests.php" class="action-btn">View Available Team Requests</a>
        </div>
        <div class="illustration">
          <!-- Placeholder for illustration in Team Formation section -->
          <img src="dashim22.png" alt="Team Formation Illustration"  />
        </div>
      </div>
    </section>


    </section>

    <section class="right-column">
      <!-- Skill Development Section -->
      <div class="section skill-development" style=" border: 3px solid #a8ab76;" >
        <h2>Skill Development 📚</h2>
        <p>Enhance your skills and knowledge with our curated learning resources!</p>
        
        <h3>Recommended Courses:</h3>
        <ul>
            <li>
                <h4>Deep Learning Specialization (Coursera)</h4>
                <p>Master deep learning techniques and neural networks.</p>
                <a href="https://www.coursera.org/specializations/deep-learning" class="action-btn" target="_blank">Start Learning</a>
            </li>
            <li>
                <h4>Blockchain for Developers (Udemy)</h4>
                <p>Learn how to build decentralized applications.</p>
                <a href="https://www.udemy.com/course/blockchain-for-developers/" class="action-btn" target="_blank">Start Learning</a>
            </li>
            <li>
            <h3>Test Your Skills:</h3>
        <p>Take a quick quiz to test your knowledge!</p>
        <a href="quiz_page.php" class="action-btn">Start Quiz Now</a>
</li>

        </ul>
      </div>

      </div>

      <!-- Career Guidance Section -->
      <div class="section career-guidance" style=" border: 3px solid #c29bcbe3;" >
        <h2>Career Guidance 🎯</h2>
        <p>Explore resources to build your career in tech!</p>
        <ul>
            <li>
                <h4>Resume Building Tips</h4>
                <p>Learn how to craft a standout resume for tech roles.</p>
                <a href="resume_tips.php" class="action-btn">Read More</a>
            </li>
            <li>
                <h4>Interview Preparation Guide</h4>
                <p>Prepare for your next job interview with these tips.</p>
                <a href="interview_guide.php" class="action-btn">Read Guide</a>
            </li>
        </ul>
      </div>
    </section>
  </main>

 <!-- Bottom Sections -->
<main class="dashboard-bottom">
  <section class="advertisement">
    <!-- Advertisement Section (Smart India Hackathon) -->
    <div class="section advertisement">
      <h3>🌟 Smart India Hackathon 2024 🌟</h3>
      <p>Join us for the largest nationwide hackathon event. Innovate, collaborate, and showcase your ideas!</p>
      <a href="https://www.smartindiahackathon.com" class="action-btn">Learn More</a>

      <!-- Flex container for image and textual content -->
      <div class="advertisement-content">
        <!-- Left side: Image -->
        <div class="illustration">
          <img src="hack.png" alt="Hackathon Advertisement Illustration" />
         
        </div>

        <!-- Right side: Textual Information -->
        <div class="advertisement-text">
          <h4>Why Participate?</h4>
          <p >Participate in Smart India Hackathon 2024 to showcase your innovation, collaborate with experts, and win exciting rewards. This is your chance to make a significant impact with your ideas!</p>
          <ul>
            <li>Get access to mentorship</li>
            <li>Compete with top innovators</li>
            <li>Win attractive prizes</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
</main>


  <!-- Footer Section -->
  <footer class="dashboard-footer">
    <p>&copy; 2024 Collaboration Platform. All rights reserved.</p>
    <div class="social-icons">
      <a href="#" target="_blank">💼 LinkedIn</a>
      <a href="#" target="_blank">🐦 Twitter</a>
      <a href="#" target="_blank">🚀 GitHub</a>
    </div>
  </footer>
</body>
</html>

