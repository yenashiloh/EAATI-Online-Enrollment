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

// Handle file upload
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

// Generate verification token
$verification_token = bin2hex(random_bytes(32));
$verification_expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

// Start transaction
$conn->begin_transaction();

try {
    // 1. Insert into users table with verification fields
    $user_stmt = $conn->prepare("INSERT INTO users (username, password, role, first_name, last_name, contact_number, email, is_verified, verification_token, verification_expires) VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, ?)");
    $user_stmt->bind_param("sssssssss", $username, $hashed_password, $role, $first_name, $last_name, $contact_number, $email, $verification_token, $verification_expires);
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
    $full_name = $first_name . ' ' . $last_name;
    
    // Prepare the student insert statement
    $student_stmt = $conn->prepare("INSERT INTO student (student_id, userId, name, email, student_house_number, student_street, student_barangay, student_municipality, image_path) VALUES (?, ?, ?, ?, NULL, NULL, NULL, NULL, ?)");
    $student_stmt->bind_param("iisss", $student_id, $userId, $full_name, $email, $image_path);
    $student_stmt->execute();
    $student_stmt->close();
    
    // Use PHPMailer to send verification email
    // Make sure to include the PHPMailer library
    require 'vendor/autoload.php'; // Path to PHPMailer autoload - adjust if needed

    // Create a new PHPMailer instance
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'c2217109@gmail.com'; // Your Gmail address
    $mail->Password   = 'jrta kken mipg wvtd'; // Your app password
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    
    // Recipients
    $mail->setFrom('c2217109@gmail.com', 'Eastern Achiever Academy of Taguig');
    $mail->addAddress($email, $first_name . ' ' . $last_name);
    
    // Content
    $mail->isHTML(true);
    $mail->Subject = "Email Verification";
    
    // Create the verification URL
    $verification_url = "http://" . $_SERVER['HTTP_HOST'] . "/onlineenrollment/verify.php?token=" . $verification_token;
    
    // Email body
    $message = file_get_contents('verification_email.php');
    $message = str_replace('{{name}}', $first_name . ' ' . $last_name, $message);
    $message = str_replace('{{verification_url}}', $verification_url, $message);
    
    $mail->Body = $message;
    
    // Send email
    if(!$mail->send()) {
        throw new Exception("Failed to send verification email: " . $mail->ErrorInfo);
    }
    
    // Commit transaction
    $conn->commit();
    
    $_SESSION['success_message'] = "Registration successful! Please check your email to verify your account.";
    header("Location: admission.php");
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