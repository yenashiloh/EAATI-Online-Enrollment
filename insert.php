<?php
session_start();

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "enrollment";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_type = $_POST['student_type'] ?? '';

if ($student_type === 'new') {
    // Handling new student registration
    // For new students, use their email as the username
    $email = $_POST['email'];
    $username = $email; // Set username to be the same as email for new students
    
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $role = "student";

    // Log the data for debugging
    error_log("New student registration - Email/Username: " . $username);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, first_name, last_name, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $hashed_password, $role, $first_name, $last_name, $contact_number, $email);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Registration successful! Please login with your email and password.";
        header("Location: login.php"); // Redirect to the login page
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
        header("Location: admission.php"); // Redirect back to the form with an error message
    }

    $stmt->close();
} elseif ($student_type === 'old') {
    // Handling old student identification
    $username = $_POST['username']; // LRN for old students
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $role = "student";

    // Log the data for debugging
    error_log("Old student verification - LRN: " . $username);

    // Query to check if student exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Student exists, update their information
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, first_name = ?, last_name = ?, contact_number = ?, email = ? WHERE username = ?");
        $updateStmt->bind_param("ssssss", $hashed_password, $first_name, $last_name, $contact_number, $email, $username);
        
        if ($updateStmt->execute()) {
            $_SESSION['success_message'] = "Information updated successfully! Please login with your LRN and new password.";
            header("Location: login.php"); // Redirect to the login page
        } else {
            $_SESSION['error_message'] = "Error updating information: " . $updateStmt->error;
            header("Location: admission.php"); // Redirect back to the form with an error message
        }
        
        $updateStmt->close();
    } else {
        $_SESSION['error_message'] = "Student with LRN {$username} not found. Please register as a new student.";
        header("Location: admission.php"); // Redirect back to the form with an error message
    }

    $stmt->close();
}

$conn->close();
exit();
?>