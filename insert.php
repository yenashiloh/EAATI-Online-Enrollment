<?php
session_start();

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$database = "enrollment";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    $_SESSION['error_message'] = "Connection failed: " . $conn->connect_error;
    header("Location: admission.php");
    exit();
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

    // First check if the email/username already exists
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Email already exists. Please use a different email.";
        header("Location: admission.php");
        $check_stmt->close();
        $conn->close();
        exit();
    }
    $check_stmt->close();

    // Log the data for debugging
    error_log("New student registration - Email/Username: " . $username);

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, first_name, last_name, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $hashed_password, $role, $first_name, $last_name, $contact_number, $email);

    try {
        if ($stmt->execute()) {
            // Store success message in session for the login page
            $_SESSION['success_message'] = "Registration successful! Please login with your email and password.";
            header("Location: login.php"); // Redirect to the login page
            exit();
        } else {
            throw new Exception($stmt->error);
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: admission.php"); // Redirect back to the form with an error message
        exit();
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

    // Check if email is already used by someone else
    $check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND username != ?");
    $check_stmt->bind_param("ss", $email, $username);
    $check_stmt->execute();
    $email_result = $check_stmt->get_result();
    
    if ($email_result->num_rows > 0) {
        $_SESSION['error_message'] = "Error: This email is already registered with another account.";
        header("Location: admission.php");
        $check_stmt->close();
        $conn->close();
        exit();
    }
    $check_stmt->close();

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
            $_SESSION['success_message'] = "Registration updated successfully! Please log in with your LRN and password.";
            header("Location: login.php"); // Redirect to login after success
            exit();
        } else {
            $_SESSION['error_message'] = "Error updating information: " . $updateStmt->error;
            header("Location: admission.php"); // Redirect back with error
            exit();
        }
        
        $updateStmt->close();
    } else {
        $_SESSION['error_message'] = "Student with LRN {$username} not found. Please register as a new student.";
        header("Location: admission.php");
        exit();
    }

    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid student type selected.";
    header("Location: admission.php");
    exit();
}

$conn->close();
exit();
?>