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

// Check if token is provided
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['error_message'] = "Invalid verification link.";
    header("Location: login.php");
    exit();
}

$token = $_GET['token'];
$current_time = date('Y-m-d H:i:s');

// Prepare statement to check if token exists and is valid
$stmt = $conn->prepare("SELECT id, first_name, last_name, verification_expires FROM users WHERE verification_token = ? AND is_verified = 0");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error_message'] = "Invalid verification token or account already verified.";
    header("Location: login.php");
    $stmt->close();
    $conn->close();
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Check if token has expired
if ($current_time > $user['verification_expires']) {
    $_SESSION['error_message'] = "Verification link has expired. Please request a new one.";
    header("Location: login.php");
    $conn->close();
    exit();
}

// Update user to verified status
$update_stmt = $conn->prepare("UPDATE users SET is_verified = 1, verification_token = NULL, verification_expires = NULL WHERE id = ?");
$update_stmt->bind_param("i", $user['id']);

if ($update_stmt->execute()) {
    $_SESSION['success_message'] = "Your account has been successfully verified!";
} else {
    $_SESSION['error_message'] = "Something went wrong. Please try again.";
}

$update_stmt->close();
$conn->close();

// Redirect to login page
header("Location: login.php");
exit();