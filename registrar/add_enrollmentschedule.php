<?php
include_once 'config1.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug function to log values
function debug_log($label, $value) {
    error_log("DEBUG [$label]: " . print_r($value, true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Log all POST data for debugging
    debug_log("POST data", $_POST);
    
    // Check if gradeGroup exists, provide default if not
    $gradeGroup = $_POST["gradeGroup"] ?? "";
    debug_log("gradeGroup", $gradeGroup);
    
    // Get start and end dates
    $startDate = $_POST["startDate"] ?? "";
    $endDate = $_POST["endDate"] ?? "";
    $status = $_POST["status"] ?? "For Review";
    
    debug_log("startDate", $startDate);
    debug_log("endDate", $endDate);
    debug_log("status", $status);
    
    // Define grade levels based on grade group
    $gradeLevels = [];
    if ($gradeGroup == "1-3") {
        $gradeLevels = [2, 3, 4]; // IDs for Grade 1, 2, 3 from your gradelevel table
    } elseif ($gradeGroup == "4-6") {
        $gradeLevels = [7, 8, 9]; // IDs for Grade 4, 5, 6
    } elseif ($gradeGroup == "7-8") {
        $gradeLevels = [10, 12]; // IDs for Grade 7, 8
    } elseif ($gradeGroup == "9-10") {
        $gradeLevels = [16, 17]; // IDs for Grade 9, 10
    }
    
    debug_log("gradeLevels", $gradeLevels);
    
    // Check if we got valid grade levels
    if (empty($gradeLevels)) {
        header("Location: enrollmentschedule.php?error=1&message=No+grade+levels+selected.");
        exit;
    }
    
    // Begin transaction
    mysqli_begin_transaction($link);
    
    try {
        // Delete existing schedules for these grade levels if they exist
        $gradeLevelIds = implode(',', $gradeLevels);
        debug_log("gradeLevelIds for SQL", $gradeLevelIds);
        
        $deleteQuery = "DELETE FROM enrollmentschedule WHERE gradelevel_id IN ($gradeLevelIds)";
        debug_log("deleteQuery", $deleteQuery);
        
        if (!mysqli_query($link, $deleteQuery)) {
            throw new Exception("Error deleting existing schedules: " . mysqli_error($link));
        }
        
        // Validate dates before inserting
        if (empty($startDate) || empty($endDate)) {
            throw new Exception("Start date and end date are required");
        }
        
        // Insert new schedules
        foreach ($gradeLevels as $levelId) {
            $insertQuery = "INSERT INTO enrollmentschedule (gradelevel_id, start_date, end_date, status) 
                           VALUES (?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($link, $insertQuery);
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . mysqli_error($link));
            }
            
            mysqli_stmt_bind_param($stmt, "isss", $levelId, $startDate, $endDate, $status);
            debug_log("Inserting for grade level", $levelId);
            
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error inserting schedule for grade level $levelId: " . mysqli_stmt_error($stmt));
            }
            
            mysqli_stmt_close($stmt);
        }
        
        // Commit the transaction
        mysqli_commit($link);

        // Redirect to success page
        header("Location: enrollmentschedule.php?added=1");
        exit;

    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($link);
        header("Location: enrollmentschedule.php?error=1&message=" . urlencode($e->getMessage()));
        exit;
    }

} else {
    // If it's not a POST request
    header("Location: enrollmentschedule.php?error=1&message=Invalid+request+method.+Use+POST+instead.");
    exit;
}
?>
