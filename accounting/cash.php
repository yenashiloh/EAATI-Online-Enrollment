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
            <h1>Cash</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Cash</li>
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
if(isset($_GET['reject']) && $_GET['reject'] == 1){
    echo "<div class='alert alert-success'>Payment rejected successfully.</div>";
}

if(isset($_GET['verified']) && $_GET['verified'] == 1){
    echo "<div class='alert alert-success'>Payment verified successfully.</div>";
}
?>
                        </div>
                        <?php
                    // Include config file
                    require_once "config1.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM transactions where payment_type= 'cash'";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Reference No.</th>";
                                        echo "<th>Payment Method</th>";
                                        echo "<th>Payment Amount</th>";
                                        echo "<th>Payment Date</th>";
                                        echo "<th>Status</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                       
                                    echo "<td>" .$row['reference_number']."</td>";
                                    echo "<td>" . $row['payment_method'];
                                    if ($row["payment_method"] == 'GCash') {
                                        // Display attachment view
                                        echo '<a href="'.$row['screenshot_path'].'" target="_blank"> - View Screenshot</a>';
                                    } else {
                                        echo '';
                                    }
                                    echo "</td>";
                                    echo "<td>" .'₱'.''. $row["payment_amount"] . "</td>";
                                    echo "<td>". $row["created_at"] . "</td>";
                                    echo "<td>";
                                        if ($row["status"] == 0) {
                                            echo '<span class="badge bg-warning text-dark">Not yet verified</span>';
                                        } else if($row["status"] == 1){
                                            echo '<span class="badge bg-success text-dark">Verified</span>';
                                        }else{
                                            echo '<span class="badge bg-danger text-dark">Rejected</span>';
                                        }
                                    echo "</td>";
                                        echo "<td>";
                                        if ($row['status'] == 0 || $row['status'] === null) {
                                        echo '<a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#verifyModal'.$row['user_id'].'" title="Verify Record" data-toggle="tooltip"><span class="bi bi-check-circle-fill"></span></a>';
                                        // Verification Modal
echo '
<div class="modal fade" id="verifyModal'.$row['user_id'].'" tabindex="-1" aria-labelledby="verifyModalLabel'.$row['user_id'].'" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verifyModalLabel'.$row['user_id'].'">Confirm Verification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to verify this record?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="verify.php?id='.$row['user_id'].'" class="btn btn-success">Verify</a>
      </div>
    </div>
  </div>
</div>';


echo '<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row['transaction_id'] . '" title="Delete Record"><span class="bi bi-x-circle-fill"></span></button>';
                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['transaction_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['transaction_id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['transaction_id'].'">Confirm Reject</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to reject this payment?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="reject.php?id='.$row['transaction_id'].'" class="btn btn-danger">Reject</a>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                                        }
                                        echo '<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal' . $row['transaction_id'] . '" title="View Record"><span class="bi bi-eye-fill"></span></button>';
// View Modal
echo '
<div class="modal fade" id="viewModal' . $row['transaction_id'] . '" tabindex="-1" aria-labelledby="viewModalLabel' . $row['transaction_id'] . '" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel' . $row['transaction_id'] . '">View Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content to display in the modal body -->
                <p>Reference No.: ' . $row['reference_number'] . '</p>
                <p>Payment Method: ' . $row['payment_method'] . '</p>
                <p>Payment Amount: ₱' . $row["payment_amount"] . '</p>
                <p>Payment Date: ' . $row["created_at"] . '</p>
                <!-- You can add more details here as needed -->
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