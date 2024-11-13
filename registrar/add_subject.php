<?php

include 'config1.php';

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate input
    $subjectName = $_POST['subjectName'];
    $subjectDescription = $_POST['subjectDescription'];

    // Prepare an insert statement
    $sql = "INSERT INTO subjects (subject_name, subject_description) VALUES (?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $param_subjectName, $param_subjectDescription);

        // Set parameters
        $param_subjectName = $subjectName;
        $param_subjectDescription = $subjectDescription;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Redirect to subject management page with success message
            header("location: subject.php?added=1");
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
