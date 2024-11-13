<?php
// Include config.php
include 'config1.php';

session_start();

$superadmin_id = $_SESSION['superadmin_id'];

if(!isset($superadmin_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
}

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate form data
    $approvalschedule_id = $_POST['approvalschedule_id'];
    $gradeLevel = $_POST['gradeLevel'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    
    // Prepare update statement
    $sql = "UPDATE approvalschedule SET gradelevel_id=?, start_date=?, end_date=? WHERE approvalschedule_id=?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "isss", $param_gradeLevel, $param_startDate, $param_endDate, $param_approvalschedule_id);
        
        // Set parameters
        $param_gradeLevel = $gradeLevel;
        $param_startDate = $startDate;
        $param_endDate = $endDate;
        $param_approvalschedule_id = $approvalschedule_id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Redirect to enrollment schedule management page with success message
            header("location: approvalschedule.php?edited=1");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of enrollmentschedule_id parameter
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $enrollmentschedule_id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM enrollmentschedule WHERE enrollmentschedule_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_enrollmentschedule_id);
            
            // Set parameters
            $param_enrollmentschedule_id = $enrollmentschedule_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                
                if(mysqli_num_rows($result) == 1){
                    // Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $gradeLevel = $row["gradelevel_id"];
                    $startDate = $row["start_date"];
                    $endDate = $row["end_date"];
                } else{
                    // URL doesn't contain valid enrollmentschedule_id parameter. Redirect to error page
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
    }  else{
        // URL doesn't contain enrollmentschedule_id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
