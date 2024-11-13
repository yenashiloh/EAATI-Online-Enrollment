<?php
// Include config file
include_once 'config.php';

// Check if the ID parameter is set and is a valid integer
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    // Check if the schedule ID parameter is set
    if(isset($_GET['schedule_id']) && is_numeric($_GET['schedule_id'])) {
        $schedule_id = $_GET['schedule_id']; // Retrieve the schedule ID from the URL
    } else {
        // If schedule ID is missing or invalid, redirect to error page
        header("location: error.php");
        exit();
    }
    
    // Prepare a delete statement
    $sql = "DELETE FROM encodedstudentsubjects WHERE encoded_id = :id";

    if($stmt = $conn->prepare($sql)){
        // Bind parameters
        $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

        // Set parameters
        $param_id = trim($_GET['id']);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Records deleted successfully. Redirect to landing page with schedule ID
            header('location:encode_student.php?deleted=1&id=' . $schedule_id);
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    unset($stmt);
    
    // Close connection
    unset($conn);
} else{
    // ID parameter is missing or invalid. Redirect to error page
    header("location: error.php");
    exit();
}
?>
