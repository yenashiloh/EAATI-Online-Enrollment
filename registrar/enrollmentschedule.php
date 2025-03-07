<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if (!isset($registrar_id)) {
    header('location:../login.php');
    exit; 
}

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Schedule of Enrollment</title>

        <?php
            include 'link.php';
        ?>

	</head>
	<body class="sidebar-light">
    <?php
    include 'header.php';
    include 'sidebar.php';
    ?>

		<div class="mobile-menu-overlay"></div>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Schedule of Enrollment</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="registrar_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Schedule of Enrollment
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
                            echo "<div class='alert alert-success'>Enrollment Schedule Deleted Successfully!</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if (isset($_GET['added']) && $_GET['added'] == 1) {
                            echo "<div class='alert alert-success'>New Enrollment Schedule Added Successfully!</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if (isset($_GET['edited']) && $_GET['edited'] == 1) {
                            echo "<div class='alert alert-success'>Updated Schedule Successfully!</div>";
                        }
                        ?>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Enrollment Schedule
                        </button>

                        <!-- Add Modal -->
                        <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addSubjectModalLabel">
                                            Enrollment Schedule
                                        </h5>
                                        <button
                                            type="button"
                                            class="close"
                                            data-dismiss="modal"
                                            aria-hidden="true"
                                        >
                                            ×
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="dateError" class="text-danger" style="display: none;">End date should be greater than start date.</div>
                                        <!-- Form to add subject -->
                                        <form id="enrollmentForm" method="post" action="add_enrollmentschedule.php">
                                        <div class="mb-3">
                                                <label for="gradeLevel" class="form-label d-block">Grade Level</label>
                                                <select class="custom-select col-12" id="gradeLevel" name="gradeLevel" required>
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
                                            <div class="modal-footer">
                                        <button
                                            type="button"
                                            class="btn btn-secondary"
                                            data-dismiss="modal"
                                        >
                                            Close
                                        </button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                         
                                        </form>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>


                    <div class="pd-20">
                    
                    </div>

                    <div class="pb-20">
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
                        $sql = "SELECT * FROM enrollmentschedule 
                                INNER JOIN gradelevel ON enrollmentschedule.gradelevel_id = gradelevel.gradelevel_id";

                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table class="data-table table stripe hover nowrap">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>No.</th>";
                                echo "<th>Grade Level</th>";
                                echo "<th>Start Date</th>";
                                echo "<th>End Date</th>";
                                echo "<th>Status</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $no = 1; // Initialize counter for numbering

                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $no . "</td>"; // Display the counter
                                    echo "<td>" . $row['gradelevel_name'] . "</td>";
                                    echo "<td>" . date("F d, Y", strtotime($row['start_date'])) . "</td>"; // Format start date
                                    echo "<td>" . date("F d, Y", strtotime($row['end_date'])) . "</td>"; // Format end date
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>";
                                    echo '<a href="#" class="m-2 edit-btn" data-toggle="modal" data-target="#editModal' . $row['enrollmentschedule_id'] . '" title="Edit Record"><span class="bi bi-pencil-fill" style="font-size: 18px;"></span></a>';

                                    // Edit Modal
                                    echo '
                                    <div class="modal fade" id="editModal' . $row['enrollmentschedule_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editModalLabel' . $row['enrollmentschedule_id'] . '" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Enrollment Schedule</h5>
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="editDateError' . $row['enrollmentschedule_id'] . '" class="text-danger" style="display: none;">End date should be greater than start date.</div>
                                                    <form id="editForm' . $row['enrollmentschedule_id'] . '" method="post" action="edit_enrollmentschedule.php">
                                                        <input type="hidden" name="enrollmentschedule_id" value="' . $row['enrollmentschedule_id'] . '">
                                                        <div class="mb-3">
                                                            <label class="form-label d-block">Grade Level</label>
                                                            <select class="custom-select col-12" name="gradeLevel" required>
                                                                <option value="">Select Grade Level</option>';
                                                                $sql_grade = "SELECT * FROM gradelevel";
                                                                $result_grade = mysqli_query($link, $sql_grade);
                                                                while ($grade_row = mysqli_fetch_assoc($result_grade)) {
                                                                    $selected = ($grade_row['gradelevel_id'] == $row['gradelevel_id']) ? "selected" : "";
                                                                    echo "<option value='" . $grade_row['gradelevel_id'] . "' " . $selected . ">" . $grade_row['gradelevel_name'] . "</option>";
                                                                }
                                                                echo '
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label d-block">Start Date</label>
                                                            <input type="date" class="form-control" name="startDate" value="' . $row['start_date'] . '" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label d-block">End Date</label>
                                                            <input type="date" class="form-control" name="endDate" value="' . $row['end_date'] . '" required>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

                                    echo '<a href="#" data-toggle="modal" data-target="#deleteModal' . $row['enrollmentschedule_id'] . '" title="Delete Record"><span class="bi bi-trash-fill" style="font-size: 18px;"></span></a>';

                                    // Delete Modal
                                    echo '
                                    <div class="modal fade" id="deleteModal' . $row['enrollmentschedule_id'] . '" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel' . $row['enrollmentschedule_id'] . '" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this schedule?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <a href="delete_enrollmentschedule.php?id=' . $row['enrollmentschedule_id'] . '" class="btn btn-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

                                    echo "</td>";
                                    echo "</tr>";

                                    $no++; // Increment counter
                                }

                                echo "</tbody>";
                                echo "</table>";

                                // Free result set
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        mysqli_close($link);
                        ?>

                    </div>

                </div>
            </div>
        </div>

		<?php
            include 'footer.php';
        ?>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
