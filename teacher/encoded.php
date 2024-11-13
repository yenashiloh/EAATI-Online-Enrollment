<?php
// Your database connection logic
include 'config.php';

// Start session and error reporting
session_start();
error_reporting(E_ALL);

// Check if the user is logged in
$teacher_id = $_SESSION['teacher_id'];
if (!isset($teacher_id)) {
    header('location:login.php');
    exit; // Stop further execution
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if selected_students[] and schedule_id are set
    if (isset($_POST['selected_students']) && isset($_POST['schedule_id'])) {
        // Get the selected schedule ID and students
        $schedule_id = $_POST['schedule_id'];
        $selected_students = $_POST['selected_students'];

        // Iterate over the selected students
        foreach ($selected_students as $student_id) {
            // Insert the student into the first table
            $sql = "INSERT INTO encodedstudentsubjects (student_id, schedule_id) VALUES (:student_id, :schedule_id)";
            $query = $conn->prepare($sql);
            $query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $query->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
            $query->execute();

            // Insert the student into the second table
            $sql2 = "INSERT INTO encodedgrades (student_id, schedule_id) VALUES (:student_id, :schedule_id)";
            $query2 = $conn->prepare($sql2);
            $query2->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $query2->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
            $query2->execute();
        }

        // Redirect back to the page with a success message
        header('location:encode_student.php?success=1&id=' . $schedule_id);
        exit;
    } else {
        // If no students were selected or schedule_id is missing, redirect back with an error message
        header('location:encode_student.php?error=no_students_selected');
        exit;
    }
} else {
    // If the form was not submitted via POST method, redirect back
    header('location:encode_student.php');
    exit;
}
?>
