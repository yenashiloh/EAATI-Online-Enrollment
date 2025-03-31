<?php
// Include config file
require_once "config1.php";

// Define variables and initialize with empty values
$subjectName = $subjectDescription = $teacherId = $gradeLevelId = "";
$subject_id = isset($_POST['subject_id']) ? $_POST['subject_id'] : "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate subject name
    $input_subjectName = trim($_POST["subjectName"]);
    if(empty($input_subjectName)){
        $subjectName_err = "Please enter a subject name.";
    } else{
        $subjectName = $input_subjectName;
    }

    // Validate subject description
    $input_subjectDescription = trim($_POST["subjectDescription"]);
    if(empty($input_subjectDescription)){
        $subjectDescription_err = "Please enter a subject description.";     
    } else{
        $subjectDescription = $input_subjectDescription;
    }
    
    // Validate teacher ID
    $input_teacherId = trim($_POST["teacherId"]);
    if(empty($input_teacherId)){
        $teacherId_err = "Please select a teacher.";
    } else{
        $teacherId = $input_teacherId;
    }
    
    // Validate grade level ID
    $input_gradeLevelId = trim($_POST["gradeLevelId"]);
    if(empty($input_gradeLevelId)){
        $gradeLevelId_err = "Please select a grade level.";
    } else{
        $gradeLevelId = $input_gradeLevelId;
    }

    // Check input errors before updating the database
    if(empty($subjectName_err) && empty($subjectDescription_err) && empty($teacherId_err) && empty($gradeLevelId_err)){
        // Prepare an update statement
        $sql = "UPDATE subjects SET subject_name=?, subject_description=?, teacher_id=?, grade_level_id=? WHERE subject_id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiii", $param_subjectName, $param_subjectDescription, $param_teacherId, $param_gradeLevelId, $param_subject_id);

            // Set parameters
            $param_subjectName = $subjectName;
            $param_subjectDescription = $subjectDescription;
            $param_teacherId = $teacherId;
            $param_gradeLevelId = $gradeLevelId;
            $param_subject_id = $subject_id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to subject management page with success message
                header("location: subject.php?edited=1");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else{
    // Check existence of subject_id parameter
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $subject_id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM subjects WHERE subject_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_subject_id);

            // Set parameters
            $param_subject_id = $subject_id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $subjectName = $row["subject_name"];
                    $subjectDescription = $row["subject_description"];
                    $teacherId = $row["teacher_id"];
                    $gradeLevelId = $row["grade_level_id"];
                } else{
                    // URL doesn't contain valid subject_id parameter. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else{
        // URL doesn't contain subject_id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>