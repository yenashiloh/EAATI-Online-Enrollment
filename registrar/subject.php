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
        <h1>Subject Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Subject</li>
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
                            echo "<div class='alert alert-success'>Subject deleted successfully.</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['added']) && $_GET['added'] == 1){
                            echo "<div class='alert alert-success'>New Subject Added Successfully.</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['edited']) && $_GET['edited'] == 1){
                            echo "<div class='alert alert-success'>Updated Successfully.</div>";
                        }
                        ?>

                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-success pull-right" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Subject
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form to add subject -->
                                        <form method="post" action="add_subject.php">
                                            <div class="mb-3">
                                                <label for="subjectName" class="form-label">Subject Name</label>
                                                <input type="text" class="form-control" id="subjectName" name="subjectName" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="subjectDescription" class="form-label">Subject Description</label>
                                                <textarea class="form-control" id="subjectDescription" name="subjectDescription" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Include config file
                    require_once "config1.php";

                    // Attempt select query execution
                    $sql = "SELECT * from subjects";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Subject Name</th>";
                                        echo "<th>Subject Description</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['subject_id'] . "</td>";
                                        echo "<td>" . $row['subject_name'] . "</td>";
                                        echo "<td>" . $row['subject_description'] . "</td>";
                                        echo "<td>";
                                        echo '<a href="#" class="r-2 view-btn" data-bs-toggle="modal" data-bs-target="#viewModal'.$row['subject_id'].'" title="View Record" data-toggle="tooltip"><span class="bi bi-eye-fill"></span></a>';

// View Modal
echo '
<div class="modal fade" id="viewModal'.$row['subject_id'].'" tabindex="-1" aria-labelledby="viewModalLabel'.$row['subject_id'].'" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel'.$row['subject_id'].'">View Subject</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Subject Name: ' . $row['subject_name'] . '</h5>
        <p>Subject Description: ' . $row['subject_description'] . '</p>
        <!-- Add any additional information you want to display here -->
      </div>
    </div>
  </div>
</div>';

                                            echo '<a href="#" class="m-2 edit-btn" data-bs-toggle="modal" data-bs-target="#editModal'.$row['subject_id'].'" title="Edit Record" data-toggle="tooltip"><span class="bi bi-pencil-fill"></span></a>';
                                            
                                            // Edit Modal
                                            echo '
                                            <div class="modal fade" id="editModal'.$row['subject_id'].'" tabindex="-1" aria-labelledby="editModalLabel'.$row['subject_id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel'.$row['subject_id'].'">Edit Subject</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <!-- Form to edit subject -->
                                                    <form id="editForm'.$row['subject_id'].'" method="post" action="edit_subject.php">
                                                        <input type="hidden" name="subject_id" value="'.$row['subject_id'].'">
                                                        <div class="mb-3">
                                                            <label for="editSubjectName'.$row['subject_id'].'" class="form-label">Subject Name</label>
                                                            <input type="text" class="form-control" id="editSubjectName'.$row['subject_id'].'" name="subjectName" value="'.$row['subject_name'].'" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editSubjectDescription'.$row['subject_id'].'" class="form-label">Subject Description</label>
                                                            <textarea class="form-control" id="editSubjectDescription'.$row['subject_id'].'" name="subjectDescription" rows="3" required>'.$row['subject_description'].'</textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';                                            echo '<a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['subject_id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';

                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['subject_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['subject_id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['subject_id'].'">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to delete this subject?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="delete_subject.php?id='.$row['subject_id'].'" class="btn btn-danger">Delete</a>
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
