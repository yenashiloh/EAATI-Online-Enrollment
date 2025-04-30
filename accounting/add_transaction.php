<?php
include "config1.php";
session_start();
if (!isset($_SESSION['accounting_id'])) {
    header('Location: login.php');
    exit;
}

// Debug - print all POST values
// echo "<pre>"; print_r($_POST); echo "</pre>"; exit;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set
    if (isset($_POST['student_id']) && isset($_POST['payment_amount']) && isset($_POST['payment_method'])) {
        $student_id = intval($_POST['student_id']);
        
        // Generate a reference number if not provided
        $reference_number = isset($_POST['reference_number']) && !empty($_POST['reference_number']) 
            ? $_POST['reference_number'] 
            : generateReferenceNumber();
        
        function generateReferenceNumber() {
            return strval(mt_rand(1000000000, 9999999999)); // 10-digit random number
        }
        
        $payment_method = $_POST['payment_method'];
        $payment_amount = floatval($_POST['payment_amount']);
        $balance = isset($_POST['balance']) ? floatval($_POST['balance']) : 0;
        
        // Set Philippine time zone and get current date
        date_default_timezone_set('Asia/Manila');
        $payment_date = date('Y-m-d'); // Current date in YYYY-MM-DD format
        
        // Get user_id for the student
        $sql_get_user_id = "SELECT userId FROM student WHERE student_id = ?";
        if ($stmt_get_user_id = mysqli_prepare($link, $sql_get_user_id)) {
            mysqli_stmt_bind_param($stmt_get_user_id, "i", $student_id);
            mysqli_stmt_execute($stmt_get_user_id);
            mysqli_stmt_bind_result($stmt_get_user_id, $userId);
            mysqli_stmt_fetch($stmt_get_user_id);
            mysqli_stmt_close($stmt_get_user_id);
            
            if ($userId) {
                $sql = "INSERT INTO transactions (user_id, reference_number, payment_method, payment_amount, payment_date, balance, created_at, status) 
                        VALUES (?, ?, ?, ?, ?, ?, NOW(), 2)";
                
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "issdsd", $userId, $reference_number, $payment_method, $payment_amount, $payment_date, $balance);
                    
                    if (mysqli_stmt_execute($stmt)) {
                        header("Location: transact.php?success=1");
                        exit();
                    } else {
                        echo "Error executing statement: " . mysqli_stmt_error($stmt);
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "Error: Unable to prepare statement. " . mysqli_error($link);
                }
            } else {
                echo "Error: Student user ID not found.";
            }
        } else {
            echo "Error: Unable to prepare user ID statement.";
        }
        mysqli_close($link);
    } else {
        echo "Error: Form fields are not set properly. Required fields: student_id, payment_amount, payment_method.";
        echo "<pre>Received: " . print_r($_POST, true) . "</pre>";
    }
}
?>