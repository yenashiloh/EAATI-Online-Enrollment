<?php

include 'config1.php';

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate input
    $gradeLevelName = $_POST['gradeLevelName'];
    $gradeLevelDescription = $_POST['gradeLevelDescription'];

    // Prepare an insert statement
    $sql = "INSERT INTO gradelevel (gradelevel_name, gradelevel_description) VALUES (?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_gradeLevelName, $param_gradeLevelDescription);

        // Set parameters
        $param_gradeLevelName = $gradeLevelName;
        $param_gradeLevelDescription = $gradeLevelDescription;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Redirect to subject management page with success message
            header("location: gradelevel.php?added=1");
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
