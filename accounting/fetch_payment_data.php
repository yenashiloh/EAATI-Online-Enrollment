<?php
// Include database configuration
include "config1.php";

// Check if grade level ID and student ID are provided
if (isset($_POST['grade_level_id']) && isset($_POST['student_id'])) {
    $grade_level_id = intval($_POST['grade_level_id']);
    $student_id = intval($_POST['student_id']);
    
    // Get payment information based on grade level
    $payment_data = array();
    
    // Get user_id for the student
    $sql_get_user_id = "SELECT userId FROM student WHERE student_id = ?";
    $user_id = null;
    
    if ($stmt = mysqli_prepare($link, $sql_get_user_id)) {
        mysqli_stmt_bind_param($stmt, "i", $student_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $user_id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
    }
    
    if ($user_id) {
        // Get payment data from payments table
        $sql_payment = "SELECT registration_fee, tuition_fee, total_whole_year, miscellaneous_fee 
                       FROM payments 
                       WHERE grade_level = ?";
        
        if ($stmt = mysqli_prepare($link, $sql_payment)) {
            mysqli_stmt_bind_param($stmt, "i", $grade_level_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $registration_fee, $tuition_fee, $total_whole_year, $miscellaneous_fee);
            
            if (mysqli_stmt_fetch($stmt)) {
                $payment_data = array(
                    'registration_fee' => $registration_fee,
                    'tuition_fee' => $tuition_fee,
                    'total_whole_year' => $total_whole_year,
                    'miscellaneous_fee' => $miscellaneous_fee
                );
            } else {
                // No payment data found for this grade level
                echo json_encode(array('error' => 'No payment data found for this grade level'));
                exit;
            }
            mysqli_stmt_close($stmt);
            
            // Get sum of all previous payments for this student
            $sql_transactions = "SELECT COALESCE(SUM(payment_amount), 0) as total_payments 
                                FROM transactions 
                                WHERE user_id = ?";
            
            if ($stmt = mysqli_prepare($link, $sql_transactions)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $total_payments);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                
                // Add total payments to the response
                $payment_data['total_payments'] = $total_payments;
                
                // Return the payment data as JSON
                echo json_encode($payment_data);
            } else {
                echo json_encode(array('error' => 'Error preparing statement: ' . mysqli_error($link)));
            }
        } else {
            echo json_encode(array('error' => 'Error preparing statement: ' . mysqli_error($link)));
        }
    } else {
        echo json_encode(array('error' => 'User ID not found for this student'));
    }
} else {
    echo json_encode(array('error' => 'Missing required parameters'));
}

// Close database connection
mysqli_close($link);
?>