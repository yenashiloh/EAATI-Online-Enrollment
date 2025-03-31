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

// Get form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$username = $_POST['username'];
$email = $_POST['email'];
$contact_number = $_POST['contact_number'];
$password = $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = "student";

// Check if username or email already exists
$check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$check_stmt->bind_param("ss", $username, $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error_message'] = "Username or email already exists. Please use different credentials.";
    header("Location: admission.php");
    $check_stmt->close();
    $conn->close();
    exit();
}
$check_stmt->close();

// Handle file upload - UPDATED CODE BASED ON YOUR EXAMPLE
$image_path = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $image_name = $_FILES['photo']['name'];
    $image_tmp = $_FILES['photo']['tmp_name'];
    $image_destination = 'uploads/images/' . $image_name;
    if (move_uploaded_file($image_tmp, $image_destination)) {
        $image_path = $image_destination;
    } else {
        $_SESSION['error_message'] = "Failed to move uploaded image file: $image_name";
        header("Location: admission.php");
        exit();
    }
}

// Start transaction
$conn->begin_transaction();

try {
    // 1. Insert into users table
    $user_stmt = $conn->prepare("INSERT INTO users (username, password, role, first_name, last_name, contact_number, email) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $user_stmt->bind_param("sssssss", $username, $hashed_password, $role, $first_name, $last_name, $contact_number, $email);
    $user_stmt->execute();
    $userId = $conn->insert_id;
    $user_stmt->close();
    
    // 2. Insert into student table with image path
    // First get the latest student ID
    $query = "SELECT MAX(student_id) as max_id FROM student";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $student_id = $row['max_id'] + 1;
    
    // Based on your database structure in Image 2, prepare the student insert statement
    // Including all the necessary fields that are visible in the screenshot
    $full_name = $first_name . ' ' . $last_name;
    
    // Prepare the student insert statement
    // We'll add the image_path to the student table directly
    $student_stmt = $conn->prepare("INSERT INTO student (student_id, userId, name, email, student_house_number, student_street, student_barangay, student_municipality, image_path) VALUES (?, ?, ?, ?, NULL, NULL, NULL, NULL, ?)");
    $student_stmt->bind_param("iisss", $student_id, $userId, $full_name, $email, $image_path);
    $student_stmt->execute();
    $student_stmt->close();
    
    // Commit transaction
    $conn->commit();
    
    $_SESSION['success_message'] = "Registration successful! Please login with your username and password.";
    header("Location: login.php");
    exit();
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error_message'] = "Error: " . $e->getMessage();
    header("Location: admission.php");
    exit();
}

$conn->close();
?>