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

    <title>Superadmin</title>
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
        <h1>Approval Schedule Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Approval Schedule</li>
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
                            echo "<div class='alert alert-success'>Approval Schedule deleted successfully.</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['added']) && $_GET['added'] == 1){
                            echo "<div class='alert alert-success'>New Approval Schedule Added Successfully.</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['edited']) && $_GET['edited'] == 1){
                            echo "<div class='alert alert-success'>Updated Schedule Successfully.</div>";
                        }
                        ?>

                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-success pull-right" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Approval Schedule
                        </button>

                        <!-- Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Approval Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <div id="dateError" class="text-danger" style="display: none;">End date should be greater than start date.</div>
                <!-- Form to add subject -->
                <form id="enrollmentForm" method="post" action="add_approvalschedule.php">
                    <div class="mb-3">
                        <label for="gradeLevel" class="form-label">Grade Level</label>
                        <select class="form-select" id="gradeLevel" name="gradeLevel" required>
                            <option value="">Select Grade Level</option>
                            <?php
                            include "config1.php";
                            // Fetch grade levels from database
                            $sql = "SELECT * FROM gradelevel";
                            $result = mysqli_query($link, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['gradelevel_id'] . "'>" . $row['gradelevel_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate" name="startDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate" name="endDate" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript validation to ensure end date is not less than start date
    document.getElementById('enrollmentForm').addEventListener('submit', function(event) {
        var startDate = new Date(document.getElementById('startDate').value);
        var endDate = new Date(document.getElementById('endDate').value);

        if (endDate < startDate) {
            event.preventDefault(); // Prevent form submission
            document.getElementById('dateError').style.display = 'block'; // Show error message
        } else {
            document.getElementById('dateError').style.display = 'none'; // Hide error message
        }
    });
</script>
                    <?php
                    // Include config file
                    require_once "config1.php";

                    // Attempt select query execution
                    $sql = "SELECT * from approvalschedule inner JOIN gradelevel on approvalschedule.gradelevel_id = gradelevel.gradelevel_id";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Grade Level</th>";
                                        echo "<th>Start Date</th>";
                                        echo "<th>End Date</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['approvalschedule_id'] . "</td>";
                                        echo "<td>" . $row['gradelevel_name'] . "</td>";
                                        echo "<td>" . $row['start_date'] . "</td>";
                                        echo "<td>" . $row['end_date'] . "</td>";
                                        echo "<td>";
                                        echo '<a href="#" class="r-2 view-btn" data-bs-toggle="modal" data-bs-target="#viewModal'.$row['approvalschedule_id'].'" title="View Record" data-toggle="tooltip"><span class="bi bi-eye-fill"></span></a>';

// View Modal
echo '
<div class="modal fade" id="viewModal'.$row['approvalschedule_id'].'" tabindex="-1" aria-labelledby="viewModalLabel'.$row['approvalschedule_id'].'" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel'.$row['approvalschedule_id'].'">View Approval Schedule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Grade Level: ' . $row['gradelevel_name'] . '</h5>
        <p>Start Date: ' . $row['start_date'] . '</p>
        <p>End Date: ' . $row['end_date'] . '</p>
        <!-- Add any additional information you want to display here -->
      </div>
    </div>
  </div>
</div>';

                                            echo '<a href="#" class="m-2 edit-btn" data-bs-toggle="modal" data-bs-target="#editModal'.$row['approvalschedule_id'].'" title="Edit Record" data-toggle="tooltip"><span class="bi bi-pencil-fill"></span></a>';
                                            
                                            // Edit Modal
echo '
<div class="modal fade" id="editModal'.$row['approvalschedule_id'].'" tabindex="-1" aria-labelledby="editModalLabel'.$row['approvalschedule_id'].'" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel'.$row['approvalschedule_id'].'">Edit Approval Schedule</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div id="editDateError'.$row['approvalschedule_id'].'" class="text-danger" style="display: none;">End date should be greater than start date.</div>
        <!-- Form to edit enrollment schedule -->
        <form id="editForm'.$row['approvalschedule_id'].'" method="post" action="edit_approvalschedule.php">
            <input type="hidden" name="approvalschedule_id" value="'.$row['approvalschedule_id'].'">
            <div class="mb-3">
                <label for="editGradeLevel'.$row['approvalschedule_id'].'" class="form-label">Grade Level</label>
                <select class="form-select" id="editGradeLevel'.$row['approvalschedule_id'].'" name="gradeLevel" required>
                    <option value="">Select Grade Level</option>';
                    // Fetch grade levels from database
                    $sql_grade = "SELECT * FROM gradelevel";
                    $result_grade = mysqli_query($link, $sql_grade);
                    while ($grade_row = mysqli_fetch_assoc($result_grade)) {
                        $selected = ($grade_row['gradelevel_id'] == $row['gradelevel_id']) ? "selected" : "";
                        echo "<option value='" . $grade_row['gradelevel_id'] . "' ".$selected.">" . $grade_row['gradelevel_name'] . "</option>";
                    }
echo '
                </select>
            </div>
            <div class="mb-3">
                <label for="editStartDate'.$row['approvalschedule_id'].'" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="editStartDate'.$row['approvalschedule_id'].'" name="startDate" value="'.$row['start_date'].'" required>
            </div>
            <div class="mb-3">
                <label for="editEndDate'.$row['approvalschedule_id'].'" class="form-label">End Date</label>
                <input type="date" class="form-control" id="editEndDate'.$row['approvalschedule_id'].'" name="endDate" value="'.$row['end_date'].'" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>';

// JavaScript validation to ensure end date is not less than start date for edit modal
echo '
<script>
document.getElementById("editForm'.$row['approvalschedule_id'].'").addEventListener("submit", function(event) {
    var startDate = new Date(document.getElementById("editStartDate'.$row['approvalschedule_id'].'").value);
    var endDate = new Date(document.getElementById("editEndDate'.$row['approvalschedule_id'].'").value);

    if (endDate < startDate) {
        event.preventDefault(); // Prevent form submission
        document.getElementById("editDateError'.$row['approvalschedule_id'].'").style.display = "block"; // Show error message
    } else {
        document.getElementById("editDateError'.$row['approvalschedule_id'].'").style.display = "none"; // Hide error message
    }
});
</script>';

                                        echo '<a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['approvalschedule_id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';

                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['approvalschedule_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['approvalschedule_id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['approvalschedule_id'].'">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to delete this schedule?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="delete_approvalschedule.php?id='.$row['approvalschedule_id'].'" class="btn btn-danger">Delete</a>
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
