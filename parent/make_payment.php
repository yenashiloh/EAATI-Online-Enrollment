<?php
session_start();

$parent_id = $_SESSION['parent_id'];

if (!isset($parent_id)) {
    header('location: login.php');
    exit; // Add exit to stop further execution
}

function generateReferenceNumber() {
    // Generate a random number
    $random_number = mt_rand(1000000000, 9999999999);
    return $random_number;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection
    include 'config1.php';

    // Get form data
    $user_id = $_SESSION['parent_id'];
    $payment_amount = $_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];
    $gcash_number = null;
    $reference_number = null;
    $payment_type = $_POST['payment_type'];
    $installment_type = $_POST['installment_type'];

    // Set GCash number or Reference number based on the selected payment method
    if ($payment_method === 'GCash') {
        $gcash_number = isset($_POST['gcash_number']) ? $_POST['gcash_number'] : null;
        $reference_number = isset($_POST['reference_number']) ? $_POST['reference_number'] : null;

        // Upload screenshot file only for GCash payments
        if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/images'; // Specify your upload directory
            $uploaded_file = $upload_dir . basename($_FILES['screenshot']['name']);
            if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $uploaded_file)) {
                $screenshot_path = $uploaded_file;
            } else {
                echo "Error uploading file.";
                exit; // Stop further execution if file upload fails
            }
        } else {
            echo "No file uploaded or upload error occurred.";
            exit; // Stop further execution if file upload is required but not provided
        }
    } elseif ($payment_method === 'Cash') {
        $reference_number = generateReferenceNumber();
        // For Cash payments, do not process file upload
        $screenshot_path = '';
    }

    // Prepare and execute the SQL statement to insert data into the database
    $sql = "INSERT INTO transactions (user_id, payment_amount, payment_method, gcash_number, reference_number, screenshot_path, payment_type, installment_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("idssssss", $user_id, $payment_amount, $payment_method, $gcash_number, $reference_number, $screenshot_path, $payment_type, $installment_type);
        if ($stmt->execute()) {
            // Payment successfully recorded
            echo "Payment successfully recorded.";
            header("Location: school_fees.php");
    exit;
        } else {
            // Error handling
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Error handling
        echo "Error: Unable to prepare SQL statement.";
    }

    // Close database connection
    $link->close();
} else {
    // If the form is not submitted, redirect to the payment form page
    header("Location: school_fees.php");
    exit;
}
?>
