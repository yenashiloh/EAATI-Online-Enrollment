<?php

include 'config.php';

session_start();

$accounting_id = $_SESSION['accounting_id'];

if(!isset($accounting_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Accounting</title>
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
            <h1>Payment</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Payment</li>
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
if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
    echo "<div class='alert alert-success'>Record deleted successfully.</div>";
}
?>

                            <a href="create_payment.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add
                                New Payment</a>
                        </div>
                        <?php
                    // Include config file
                    require_once "config1.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM payments inner join gradelevel on payments.grade_level = gradelevel.gradelevel_id";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Grade Level</th>";
                                        echo "<th>Upon Enrollment</th>";
                                       
                                        echo "<th>Total Tuition Fee</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                       
                                        echo "<td>" .$row['gradelevel_name']."</td>";
                                        echo "<td>" . $row['upon_enrollment'] . "</td>";

                                        echo "<td>" . $row['total_whole_year'] . "</td>";
                                        echo "<td>";
                                        echo '<button type="button" class="btn btn-primary r-2" title="View Record" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#viewModal'.$row['payment_id'].'"><span class="bi bi-eye-fill"></span></button>';

                                        echo '<button type="button" class="btn btn-secondary m-2" title="Update Record" data-toggle="tooltip" onclick="location.href=\'edit_payment.php?id='. $row['payment_id'] .'\'"><span class="bi bi-pencil-fill"></span></button>';
                                        echo '<button type="button" class="btn btn-danger" title="Delete Record" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['payment_id'].'"><span class="bi bi-trash-fill"></span></button>';
                                        
                                            
                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['payment_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['payment_id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['payment_id'].'">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to delete this record?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="delete.php?id='.$row['payment_id'].'" class="btn btn-danger">Delete</a>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';

                                            // View Modal
echo '
<div class="modal fade" id="viewModal'.$row['payment_id'].'" tabindex="-1" aria-labelledby="viewModalLabel'.$row['payment_id'].'" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel'.$row['payment_id'].'">View Payment Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>Grade Level: '.$row['gradelevel_name'].'</h6>
        <p>Upon Enrollment: '.$row['upon_enrollment'].'</p>
        <p>Partial Upon: '.$row['partial_upon'].'</p>
        <p>Total Tuition Fee w/o Books: '.$row['total_whole_year'].'</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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