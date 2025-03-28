<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect the form data
    $name = $_POST['name'];
    $skills = $_POST['skills'];
    $year = $_POST['year'];
    $branch = $_POST['branch'];
    $interests = $_POST['interests'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    $github = $_POST['github'];
    $email = $_POST['email'];

    // Check if email already exists in students or login table
    $email_check = $conn->prepare("SELECT email FROM students WHERE email = ? UNION SELECT email FROM login WHERE email = ?");
    $email_check->bind_param("ss", $email, $email);
    $email_check->execute();
    $email_check_result = $email_check->get_result();

    if ($email_check_result->num_rows > 0) {
        echo "Email already exists!";
        exit();
    }

    // Insert into students table
    $stmt = $conn->prepare("INSERT INTO students (name, skills, year, branch, interests, password, github, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisssss", $name, $skills, $year, $branch, $interests, $password, $github, $email);

    if ($stmt->execute()) {
        // Insert into login table
        $stmt_login = $conn->prepare("INSERT INTO login (email, password) VALUES (?, ?)");
        $stmt_login->bind_param("ss", $email, $password);

        if ($stmt_login->execute()) {
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit();
        } else {
            echo "Error in login table insertion: " . $stmt_login->error;
        }
    } else {
        echo "Error in students table insertion: " . $stmt->error;
    }

    // Close the prepared statements
    $stmt->close();
    $stmt_login->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-purple-200 to-yellow-200">
    <!-- Main Section -->
    <section class="min-h-screen flex items-center justify-center py-8">
        <div class="container mx-auto flex items-center justify-between bg-white rounded-lg shadow-lg p-6 md:p-8 max-w-5xl">
            <!-- Left side - Registration Form -->
            <div class="w-full md:w-1/2 px-4">
                <h2 class="text-3xl font-bold text-gray-800 mb-6" style="font-family: 'Poppins'; font-size:24px;">Create Your HACKSHASTRA Account</h2>
                <p class="text-gray-600 mb-6">Fill in the details below to register. Join our community and start forming teams for your next big competition!</p>

                <form id="studentForm" method="POST">
                    <!-- Name and Skills -->
                    <div class="flex gap-6 mb-6">
                        <div class="w-1/2">
                            <label for="name" class="text-gray-700 font-medium">Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter your name" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="w-1/2">
                            <label for="skills" class="text-gray-700 font-medium">Skills</label>
                            <input type="text" id="skills" name="skills" placeholder="e.g. JavaScript, React" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <!-- Year of Study, Branch -->
                    <div class="flex gap-6 mb-6">
                        <div class="w-1/2">
                            <label for="year" class="text-gray-700 font-medium">Year of Study</label>
                            <select id="year" name="year" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="" disabled selected>Select your year of study</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                        <div class="w-1/2">
                            <label for="branch" class="text-gray-700 font-medium">Branch Name</label>
                            <input type="text" id="branch" name="branch" placeholder="e.g. Computer Science" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>

                    <!-- Interests -->
                    <div class="mb-6">
                        <label for="interests" class="text-gray-700 font-medium">Interests</label>
                        <textarea id="interests" name="interests" rows="4" placeholder="Describe your interests here" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="text-gray-700 font-medium">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="text-gray-700 font-medium">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <!-- GitHub Link -->
                    <div class="mb-6">
                        <label for="github" class="text-gray-700 font-medium">GitHub Link</label>
                        <input type="url" id="github" name="github" placeholder="Enter your GitHub link" class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="mb-6">
                        <button type="submit" class="w-full py-3 px-6 bg-blue-500 text-white font-medium rounded-lg focus:outline-none hover:bg-blue-600">Register</button>
                    </div>
                </form>
            </div>

 <!-- Right side - Illustration / Image -->
 <div class="w-1/2 hidden md:block px-4">
                <img src="teamreg.png" alt="Illustration" class="w-full h-auto">
            </div>

        </div>
    </section>
</body>
</html>

