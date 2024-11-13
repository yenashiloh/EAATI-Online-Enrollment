<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if(!isset($registrar_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Registrar</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include 'asset.php';?>

</head>

<body>

<?php 
    include 'header.php';
    include 'sidebar.php';
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Enrollment</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Enrollment</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['verified']) && $_GET['verified'] == 1){
                            echo "<div class='alert alert-success'>Record verified successfully.</div>";
                        }
                        ?>
                    </div>
                    <?php
                    // Include config file
                    require_once "config1.php";
                    
                    $today_date = date("Y-m-d");
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM student INNER JOIN users ON student.userId = users.id inner join gradelevel on student.grade_level = gradelevel.gradelevel_id inner join approvalschedule on student.grade_level = approvalschedule.gradelevel_id
                            WHERE approvalschedule.start_date <= CURDATE() AND approvalschedule.end_date >= CURDATE()";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
                                echo "<thead>";
                                    echo "<tr>";                                      
                                        echo "<th>Grade Level</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Date of Birth</th>";
                                        echo "<th>Email</th>";
                                        echo "<th>Status</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . $row['gradelevel_name'] . "</td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['dob'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>" . ($row['isVerified'] == 1 ? 'Verified' : ($row['isVerified'] == 0 ? 'Not Verified' : 'Enrolled')) . "</td>";
                                        echo "<td>";
                                        if ($row['isVerified'] == 0) {
                                        echo '<a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#verifyModal'.$row['student_id'].'" title="Verify Record" data-toggle="tooltip"><span class="bi bi-check-circle-fill"></span></a>';
                                        // Verification Modal
echo '
<div class="modal fade" id="verifyModal'.$row['student_id'].'" tabindex="-1" aria-labelledby="verifyModalLabel'.$row['student_id'].'" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verifyModalLabel'.$row['student_id'].'">Confirm Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to verify this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="verify.php?id='.$row['student_id'].'" class="btn btn-success">Verify</a>
      </div>
    </div>
  </div>
</div>';
                        }
                        echo '<a href="view_record.php?id='.$row['student_id'].'" class="btn btn-info" title="View Record"><span class="bi bi-eye-fill"></span></a>';

                                        
                                            echo '  ';
                                            echo '<a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['student_id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';

                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['student_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['student_id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['student_id'].'">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to delete this record?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="delete_enrollment.php?id='.$row['student_id'].'" class="btn btn-danger">Delete</a>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>

                </div>
            </div>

        </div>
        </div>
    </section>

</main><!-- End #main -->

<?php
    include 'footer.php';
    include 'script.php';
?>

</body>

</html>
