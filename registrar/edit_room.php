<?php
// Include config.php
include 'config1.php';
session_start();
$registrar_id = $_SESSION['registrar_id'];
if(!isset($registrar_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
}

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate form data
    $room_id = $_POST['room_id'];
    $roomName = $_POST['editRoomName'];
    $roomDescription = $_POST['editRoomDescription'];
    $gradeLevel = $_POST['editGradeLevel']; // Add grade level
    
    // Prepare update statement
    $sql = "UPDATE rooms SET room_name=?, room_description=?, gradelevel_id=? WHERE room_id=?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssii", $param_roomName, $param_roomDescription, $param_gradeLevel, $param_room_id);
        
        // Set parameters
        $param_roomName = $roomName;
        $param_roomDescription = $roomDescription;
        $param_gradeLevel = $gradeLevel; // Add grade level parameter
        $param_room_id = $room_id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Redirect to room management page with success message
            header("location: room.php?edited=1");
            exit();
        } else{
            // Error handling
            echo "Oops! Something went wrong. Please try again later.";
            error_log("MySQL Error: " . mysqli_error($link)); // Log the specific error
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else {
    // If not a POST request, redirect or handle accordingly
    header("location: room.php");
    exit();
}
?>