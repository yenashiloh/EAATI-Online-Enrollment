<?php

include 'config.php';

session_start();

$superadmin_id = $_SESSION['superadmin_id'];

if(!isset($superadmin_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Dashboard</title>
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
            <h1>User Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">User</li>
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

                            <a href="create_user.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add
                                New Employee</a>
                        </div>
                        <?php
                    // Include config file
                    require_once "config1.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM users";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Username</th>";
                                        echo "<th>Role</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" .$row['first_name'].' '.$row['last_name']. "</td>";
                                        echo "<td>" . $row['username'] . "</td>";
                                        echo "<td>" . $row['role'] . "</td>";
                                        echo "<td>";
                                            // View Record Link
                                            echo '<a href="#" class="r-2 view-record" data-bs-toggle="modal" data-bs-target="#viewModal'.$row['id'].'" title="View Record" data-toggle="tooltip"><span class="bi bi-eye-fill"></span></a>';

                                            // View Modal
                                            echo '
                                            <div class="modal fade" id="viewModal'.$row['id'].'" tabindex="-1" aria-labelledby="viewModalLabel'.$row['id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="viewModalLabel'.$row['id'].'">View Record</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <!-- Display record details here -->
                                                    <p>Name: ' . $row['first_name'].' '.$row['last_name'] . '</p>
                                                    <p>Username: ' . $row['username'] . '</p>
                                                    <p>Role: ' . $row['role'] . '</p>
                                                    <!-- Add more details if needed -->
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                                            
                                            echo '<a href="edit_user.php?id='. $row['id'] .'" class="m-2" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-fill"></span></a>';
                                            echo '<a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';
                                            
                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['id'].'">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to delete this record?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="delete.php?id='.$row['id'].'" class="btn btn-danger">Delete</a>
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