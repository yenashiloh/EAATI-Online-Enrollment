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
    error_log("LRN (username) received: " . $_POST['username']);

    $usernameNew = $_POST['usernameNew'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $role = "student";

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, first_name, last_name, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $usernameNew, $hashed_password, $role, $first_name, $last_name, $contact_number, $email);

    if ($stmt->execute()) {
        header("Location: login.php"); // Redirect to a success page
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
        header("Location: admission.php"); // Redirect back to the form with an error message
    }

    $stmt->close();
} elseif ($student_type === 'old') {
    // Handling old student identification
    $username = $_POST['username'];

    // Query to check if student exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: login.php"); // Redirect to the login or profile update page
    } else {
        $_SESSION['error_message'] = "Student not found. Please register as a new student.";
        header("Location: admission.php"); // Redirect back to the form with an error message
    }

    $stmt->close();
}

$conn->close();
exit();
?>
