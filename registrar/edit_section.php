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
    $section_id = $_POST['section_id'];
    $sectionName = $_POST['editSectionName'];
    $sectionDescription = $_POST['editSectionDescription'];
    
    // Prepare update statement
    $sql = "UPDATE sections SET section_name=?, section_description=? WHERE section_id=?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ssi", $param_sectionName, $param_sectionDescription, $param_section_id);
        
        // Set parameters
        $param_sectionName = $sectionName;
        $param_sectionDescription = $sectionDescription;
        $param_section_id = $section_id;
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Redirect to section management page with success message
            header("location: section.php?edited=1");
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
    // Check existence of section_id parameter
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $section_id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM sections WHERE section_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_section_id);
            
            // Set parameters
            $param_section_id = $section_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                
                if(mysqli_num_rows($result) == 1){
                    // Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $sectionName = $row["section_name"];
                    $sectionDescription = $row["section_description"];
                } else{
                    // URL doesn't contain valid section_id parameter. Redirect to error page
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
        // URL doesn't contain section_id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
