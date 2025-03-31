<?php
include 'config1.php';

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate and sanitize input
    $subjectName = trim($_POST['subjectName']);
    $subjectDescription = trim($_POST['subjectDescription']);
    $gradeLevel = $_POST['gradeLevel'];
    $subjectTeacher = $_POST['subjectTeacher'];

    // Validate inputs
    if(empty($subjectName) || empty($subjectDescription) || empty($gradeLevel) || empty($subjectTeacher)){
        $error = "All fields are required.";
    } else {
        // Prepare an insert statement
        $sql = "INSERT INTO subjects (subject_name, subject_description, grade_level_id, teacher_id) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssii", $param_subjectName, $param_subjectDescription, $param_gradeLevel, $param_subjectTeacher);

            // Set parameters
            $param_subjectName = $subjectName;
            $param_subjectDescription = $subjectDescription;
            $param_gradeLevel = $gradeLevel;
            $param_subjectTeacher = $subjectTeacher;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to subject management page with success message
                header("location: subject.php?added=1");
                exit();
            } else{
                $error = "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
}

// Fetch grade levels for dropdown
$gradeLevels = [];
$gradeQuery = "SELECT gradelevel_id, gradelevel_name FROM gradelevel";
$gradeResult = mysqli_query($link, $gradeQuery);
while($row = mysqli_fetch_assoc($gradeResult)){
    $gradeLevels[] = $row;
}

// Fetch teachers for dropdown (users with 'teacher' role)
$teachers = [];
$teacherQuery = "SELECT id, first_name, last_name FROM users WHERE role = 'teacher'";
$teacherResult = mysqli_query($link, $teacherQuery);
while($row = mysqli_fetch_assoc($teacherResult)){
    $teachers[] = $row;
}
?>