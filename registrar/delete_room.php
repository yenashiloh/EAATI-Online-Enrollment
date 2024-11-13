<?php
// Include config file
include_once 'config.php';

// Check if the ID parameter is set and is a valid integer
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    // Prepare a delete statement
    $sql = "DELETE FROM rooms WHERE room_id = :id";

    if($stmt = $conn->prepare($sql)){
        // Bind parameters
        $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

        // Set parameters
        $param_id = trim($_GET['id']);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Records deleted successfully. Redirect to landing page
            header("location: room.php?deleted=1");
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
