<?php
require_once 'config1.php'; // Use the same config file as the main script
session_start();
error_reporting(E_ALL);

$teacher_id = $_SESSION['teacher_id'] ?? null;
if (!$teacher_id) {
    header('location:../login.php');
    exit; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Retrieve and validate input data
        $studentId = filter_input(INPUT_POST, 'studentId', FILTER_VALIDATE_INT);
        $userId = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_INT); // Validate userId
        $scheduleId = filter_input(INPUT_POST, 'scheduleId', FILTER_VALIDATE_INT);
        $firstQuarter = filter_input(INPUT_POST, 'firstQuarter', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 100]
        ]);
        $secondQuarter = filter_input(INPUT_POST, 'secondQuarter', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 100]
        ]);
        $thirdQuarter = filter_input(INPUT_POST, 'thirdQuarter', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 100]
        ]);
        $fourthQuarter = filter_input(INPUT_POST, 'fourthQuarter', FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 0, 'max_range' => 100]
        ]);
        $encodedGradesId = filter_input(INPUT_POST, 'encodedGradesId', FILTER_VALIDATE_INT);

        // Validate all inputs
        if ($studentId === false || $userId === false || $scheduleId === false || 
            $firstQuarter === false || $secondQuarter === false || 
            $thirdQuarter === false || $fourthQuarter === false) {
            throw new Exception("Invalid input data");
        }

        // Check if an entry already exists
        $checkSql = "SELECT encodedgrades_id FROM encodedgrades 
                     WHERE student_id = ? AND schedule_id = ?";
        $checkStmt = $link->prepare($checkSql);
        $checkStmt->bind_param("ii", $studentId, $scheduleId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $existingEntry = $checkResult->fetch_assoc();

        if ($existingEntry) {
            // Update existing entry
            $sql = "UPDATE encodedgrades 
                    SET quarter1 = ?, quarter2 = ?, quarter3 = ?, quarter4 = ?, status = 'For Review', userId = ?
                    WHERE student_id = ? AND schedule_id = ?";
            $stmt = $link->prepare($sql);
            $stmt->bind_param("iiiiiii", $firstQuarter, $secondQuarter, $thirdQuarter, $fourthQuarter, $userId, $studentId, $scheduleId);
        } else {
            // Insert new entry with status as a parameter
            $sql = "INSERT INTO encodedgrades (student_id, schedule_id, userId, quarter1, quarter2, quarter3, quarter4, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $link->prepare($sql);
            $status = 'For Review'; // Define status as a variable
            $stmt->bind_param("iiiiiiis", $studentId, $scheduleId, $userId, $firstQuarter, $secondQuarter, $thirdQuarter, $fourthQuarter, $status);
        }

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back with success message
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&grade_saved=true");
            exit();
        } else {
            throw new Exception("Failed to save grades: " . $link->error);
        }
    } catch (Exception $e) {
        // Log the error
        error_log($e->getMessage());
        
        // Redirect back with an error message
        header("Location: " . $_SERVER['HTTP_REFERER'] . "&error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Not a POST request
    header('location:index.php');
    exit;
}
?>