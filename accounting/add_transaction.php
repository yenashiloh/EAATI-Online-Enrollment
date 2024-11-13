<?php
// Include config file
include "config1.php";

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['accounting_id'])) {
    header('Location: login.php');
    exit;
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    if(isset($_POST['student_id']) && isset($_POST['reference_number']) && isset($_POST['payment_amount']) && isset($_POST['payment_date'])) {
        $student_id = $_POST['student_id'];
        $reference_number = $_POST['reference_number'];
        $payment_method = "Cash"; // Since it's disabled and fixed to Cash
        $payment_amount = $_POST['payment_amount'];
        $payment_date = $_POST['payment_date'];
        $is_paid = isset($_POST['is_paid']) ? $_POST['is_paid'] : null;

        // Prepare an insert statement
        $sql = "INSERT INTO transactions (user_id, reference_number, payment_method, payment_amount, payment_date, created_at, status) 
                VALUES (?, ?, ?, ?, ?, NOW(), 2)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "issds", $param_user_id, $param_reference_number, $param_payment_method, $param_payment_amount, $param_payment_date);

            // Set parameters
            $param_user_id = $student_id;
            $param_reference_number = $reference_number;
            $param_payment_method = $payment_method;
            $param_payment_amount = $payment_amount;
            $param_payment_date = $payment_date;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // If the transaction is recorded successfully, update the student's isPaidUpon field
                if ($is_paid !== null) {
                    $update_sql = "UPDATE student SET isPaidUpon = ? WHERE student_id = ?";
                    if ($update_stmt = mysqli_prepare($link, $update_sql)) {
                        mysqli_stmt_bind_param($update_stmt, "ii", $param_is_paid, $param_student_id);

                        // Set parameters
                        $param_is_paid = $is_paid;
                        $param_student_id = $student_id;

                        // Attempt to execute the update statement
                        mysqli_stmt_execute($update_stmt);
                        
                        // Close statement
                        mysqli_stmt_close($update_stmt);
                    }
                }

                // Records created successfully. Redirect to the transactions page
                header("Location: transact.php?success=1");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
                echo "Error: " . mysqli_error($link); // Display MySQL error if any
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: Unable to prepare statement.";
            echo "Error: " . mysqli_error($link); // Display MySQL error if any
        }
    } else {
        echo "Error: Form fields are not set properly.";
    }
    
    // Close connection
    mysqli_close($link);
}
?>
