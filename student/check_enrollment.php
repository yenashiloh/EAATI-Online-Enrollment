<?php
// Include database connection logic
include 'config.php';

// Check if the grade level is provided in the request
if(isset($_POST['grade_level'])) {
    // Retrieve grade level from the request
    $selectedGradeLevel = $_POST['grade_level'];
    
    // Get today's date
    $todayDate = date("Y-m-d");
    
    // Query to check if there's an enrollment schedule for today and the selected grade level
    $query = $conn->prepare("SELECT * FROM enrollmentschedule WHERE gradelevel_id = :gradelevel_id AND start_date <= :today_date AND end_date >= :today_date");
    $query->bindParam(':gradelevel_id', $selectedGradeLevel, PDO::PARAM_INT);
    $query->bindParam(':today_date', $todayDate, PDO::PARAM_STR);
    $query->execute();
    
    // Check if there's a row returned (enrollment schedule exists for today and the selected grade level)
    if($query->rowCount() > 0) {
        // Enrollment schedule exists for today and the selected grade level
        // Send a JSON response indicating success
        echo json_encode(array('enrollmentExists' => true));
    } else {
        // No enrollment schedule exists for today and the selected grade level
        // Send a JSON response indicating failure
        echo json_encode(array('enrollmentExists' => false));
    }
} else {
    // Grade level parameter is not provided in the request
    // Send a JSON response with an error message
    echo json_encode(array('error' => 'Grade level parameter is missing.'));
}
?>
