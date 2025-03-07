<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if(!isset($registrar_id)){
   header('location:../login.php');
   exit; 
}

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Grade Level</title>

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
									<h4>Grade Level</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="teacher_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Grade Level
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
                            echo "<div class='alert alert-success'>Grade Level Deleted Successfully!</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['added']) && $_GET['added'] == 1){
                            echo "<div class='alert alert-success'>New Grade Level Added Successfully!</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['edited']) && $_GET['edited'] == 1){
                            echo "<div class='alert alert-success'>Updated Successfully!</div>";
                        }
                        ?>

                        <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Grade Level
                        </button>
                        
                        
                        
                        <div class="pb-20">
                        <?php
                        // Include config file
                        require_once "config1.php";

                        // Attempt select query execution
                        $sql = "SELECT * from gradelevel ORDER by gradelevel_name ASC";
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table class="data-table table stripe hover nowrap">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>No.</th>"; // Added column for numbering
                                echo "<th>Grade Level</th>";
                                echo "<th>Grade Level Description</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $counter = 1; // Initialize counter
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $counter . "</td>"; // Display counter value
                                    echo "<td>" . $row['gradelevel_name'] . "</td>";
                                    echo "<td>" . $row['gradelevel_description'] . "</td>";
                                    echo "<td>";
                                    echo '<a href="#" class="edit-btn" data-toggle="modal" data-target="#editGradeLevelModal' . $row['gradelevel_id'] . '"><span class="bi bi-pencil-fill" style="font-size: 18px;"></span></a>';

                                    // Edit Grade Level Modal
                                    echo '<div class="modal fade" id="editGradeLevelModal' . $row['gradelevel_id'] . '" tabindex="-1" aria-labelledby="editGradeLevelModalLabel' . $row['gradelevel_id'] . '" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editGradeLevelModalLabel' . $row['gradelevel_id'] . '">Edit Grade Level</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Form to edit grade level -->
                                                    <form method="post" action="edit_gradelevel.php">
                                                        <div class="mb-3">
                                                            <label for="editGradeLevelName" class="form-label">Grade Level Name</label>
                                                            <input type="text" class="form-control" id="editGradeLevelName" name="editGradeLevelName" value="' . $row['gradelevel_name'] . '" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editGradeLevelDescription" class="form-label">Grade Level Description</label>
                                                            <textarea class="form-control" id="editGradeLevelDescription" name="editGradeLevelDescription" rows="3" required>' . $row['gradelevel_description'] . '</textarea>
                                                        </div>
                                                        <input type="hidden" name="gradelevel_id" value="' . $row['gradelevel_id'] . '">
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

                                    echo '<a href="#" data-toggle="modal" data-target="#deleteModal' . $row['gradelevel_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill ml-2" style="font-size: 18px;"></span></a>';

                                    // Delete Modal
                                    echo '<div class="modal fade" id="deleteModal' . $row['gradelevel_id'] . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row['gradelevel_id'] . '" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel' . $row['gradelevel_id'] . '">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this grade level?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <a href="delete_gradelevel.php?id=' . $row['gradelevel_id'] . '" class="btn btn-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';

                                    echo "</td>";
                                    echo "</tr>";

                                    $counter++; // Increment counter
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

                        // Close connection
                        mysqli_close($link);
                        ?>


              <!-- Add Room Modal -->
                <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addSubjectModalLabel">Add Room</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <!-- Form to add subject -->
                                <form method="post" action="add_gradelevel.php">
                                    <div class="mb-3">
                                        <label for="gradeLevelName" class="form-label">Grade Level Name</label>
                                        <select class="form-control" id="gradeLevelName" name="gradeLevelName" required>
                                            <option value="">Select Grade Level</option>
                                            <option value="Grade 1">Grade 1</option>
                                            <option value="Grade 2">Grade 2</option>
                                            <option value="Grade 3">Grade 3</option>
                                            <option value="Grade 4">Grade 4</option>
                                            <option value="Grade 5">Grade 5</option>
                                            <option value="Grade 6">Grade 6</option>
                                            <option value="Grade 7">Grade 7</option>
                                            <option value="Grade 8">Grade 8</option>
                                            <option value="Grade 9">Grade 9</option>
                                            <option value="Grade 10">Grade 10</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gradeLevelDescription" class="form-label">Grade Level Description</label>
                                        <textarea class="form-control" id="gradeLevelDescription" name="gradeLevelDescription" rows="3" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
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
