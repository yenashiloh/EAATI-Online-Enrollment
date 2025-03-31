<?php
include "config1.php";
header('Content-Type: application/json');

if (isset($_POST['grade_level'])) {
    $gradeLevelId = $_POST['grade_level'];
    $currentDate = date('Y-m-d');
    
    // Get the enrollment schedule for the selected grade level
    $sql = "SELECT * FROM enrollmentschedule WHERE gradelevel_id = ?";
    
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $gradeLevelId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $startDate = $row['start_date'];
        $endDate = $row['end_date'];
        $status = $row['status'];
        
        // Check if current date is within enrollment period
        if ($currentDate >= $startDate && $currentDate <= $endDate && $status === 'Approved') {
            echo json_encode([
                'enrollmentExists' => true,
                'status' => $status
            ]);
        } else {
            // Either current date is outside enrollment period or status is not Approved
            echo json_encode([
                'enrollmentExists' => false,
                'status' => $status,
                'beforeStart' => ($currentDate < $startDate),
                'afterEnd' => ($currentDate > $endDate)
            ]);
        }
    } else {
        // No enrollment schedule exists for this grade level
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