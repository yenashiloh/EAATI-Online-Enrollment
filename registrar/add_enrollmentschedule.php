<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include_once 'config1.php';

    // Define variables and initialize with empty values
    $gradeLevel = $startDate = $endDate = "";

    // Process form data when the form is submitted
    $gradeLevel = $_POST['gradeLevel'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Attempt to insert the enrollment schedule into the database
    $sql = "INSERT INTO enrollmentschedule (gradelevel_id, start_date, end_date) VALUES ('$gradeLevel', '$startDate', '$endDate')";
    if (mysqli_query($link, $sql)) {
        // Redirect to the enrollment schedule page with success message
        header("location: enrollmentschedule.php?added=1");
        exit;
    } else {
        // If insertion fails, display an error message
        echo "Error: " . $sql . "<br>" . mysqli_error($link);
    }

    // Close database connection
    mysqli_close($link);
}
?>
