<?php

include 'config1.php';

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate input
    $roomName = $_POST['roomName'];
    $roomDescription = $_POST['roomDescription'];

    // Prepare an insert statement
    $sql = "INSERT INTO rooms (room_name, room_description) VALUES (?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_roomName, $param_roomDescription);

        // Set parameters
        $param_roomName = $roomName;
        $param_roomDescription = $roomDescription;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Redirect to subject management page with success message
            header("location: room.php?added=1");
            exit();
        } else{
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>
