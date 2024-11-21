<?php
include "config1.php";

header('Content-Type: application/json');

if (isset($_POST['grade_level'])) {
    $gradeLevelId = $_POST['grade_level'];
    $currentDate = date('Y-m-d');

    // Check if there's an active enrollment schedule for the selected grade level
    $sql = "SELECT * FROM enrollmentschedule 
            WHERE gradelevel_id = ? 
            AND ? BETWEEN start_date AND end_date";
    
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "is", $gradeLevelId, $currentDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Return both enrollment existence and status
        echo json_encode([
            'enrollmentExists' => true,
            'status' => $row['status']
        ]);
    } else {
        echo json_encode([
            'enrollmentExists' => false,
            'status' => null
        ]);
    }
} else {
    echo json_encode([
        'enrollmentExists' => false,
        'status' => null,
        'error' => 'Grade level not provided'
    ]);
}
?>