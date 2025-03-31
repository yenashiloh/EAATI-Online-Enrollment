<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once 'config1.php';
    
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Get and validate form data
    if (!isset($_POST['editGroup']) || !isset($_POST['editStartDate']) || !isset($_POST['editEndDate'])) {
        echo "Error: Missing required fields";
        exit;
    }
    
    $editGroup = $_POST['editGroup'];
    $startDate = $_POST['editStartDate'];
    $endDate = $_POST['editEndDate'];
    $status = "For Review";

    // Validate dates
    if (strtotime($endDate) <= strtotime($startDate)) {
        echo "Error: End date must be after start date";
        exit;
    }

    // Define gradelevel_id mappings
    $gradeLevels = [
        "1-3" => [2, 3, 4],  // IDs for Grade 1, 2, 3
        "4-6" => [7, 8, 9],  // IDs for Grade 4, 5, 6
        "7-8" => [10, 12],   // IDs for Grade 7, 8
        "9-10" => [16, 17],  // IDs for Grade 9, 10
        "11-12" => [18, 19]  // IDs for Grade 11, 12
    ];

    if (!array_key_exists($editGroup, $gradeLevels)) {
        echo "Error: Invalid grade group";
        exit;
    }

    $gradeLevelIds = implode(',', $gradeLevels[$editGroup]);

    mysqli_begin_transaction($link);

    try {
        // Use prepared statement
        $updateSql = "UPDATE enrollmentschedule SET 
                     start_date = ?, 
                     end_date = ?,
                     status = ? 
                     WHERE FIND_IN_SET(gradelevel_id, ?)";

        $stmt = mysqli_prepare($link, $updateSql);
        
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . mysqli_error($link));
        }

        mysqli_stmt_bind_param($stmt, "ssss", $startDate, $endDate, $status, $gradeLevelIds);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error updating records: " . mysqli_stmt_error($stmt));
        }

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            mysqli_commit($link);
            header("location: enrollmentschedule.php?updated=1");
            exit;
        } else {
            echo "No records updated.";
        }

        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        mysqli_rollback($link);
        echo "Error: " . $e->getMessage();
    }
    
    mysqli_close($link);
}
?>
