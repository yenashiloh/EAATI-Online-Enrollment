<?php
// Include config.php
include 'config1.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if(!isset($registrar_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
}

// Check if form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate form data
    $gradelevel_id = $_POST['gradelevel_id'];
    $gradeLevelName = $_POST['editGradeLevelName'];
    $gradeLevelDescription = $_POST['editGradeLevelDescription'];
    
    // Prepare update statement
    $sql = "UPDATE gradelevel SET gradelevel_name=?, gradelevel_description=? WHERE gradelevel_id=?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssi", $param_gradeLevelName, $param_gradeLevelDescription, $param_gradelevel_id);
        
        // Set parameters
        $param_gradeLevelName = $gradeLevelName;
        $param_gradeLevelDescription = $gradeLevelDescription;
        $param_gradelevel_id = $gradelevel_id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Redirect to grade level management page with success message
            header("location: gradelevel.php?edited=1");
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
    // Check existence of gradelevel_id parameter
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $gradelevel_id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM gradelevel WHERE gradelevel_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_gradelevel_id);
            
            // Set parameters
            $param_gradelevel_id = $gradelevel_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                
                if(mysqli_num_rows($result) == 1){
                    // Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $gradeLevelName = $row["gradelevel_name"];
                    $gradeLevelDescription = $row["gradelevel_description"];
                } else{
                    // URL doesn't contain valid gradelevel_id parameter. Redirect to error page
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
        // URL doesn't contain gradelevel_id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
