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
                    <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Subject
                        </button>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
                            echo "<div class='alert alert-success'>Subject Deleted Successfully!</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['added']) && $_GET['added'] == 1){
                            echo "<div class='alert alert-success'>New Subject Added Successfully!</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['edited']) && $_GET['edited'] == 1){
                            echo "<div class='alert alert-success'>Updated Successfully!</div>";
                        }
                        ?>
                    <?php
                    // Include config file
                    require_once "config1.php";

                    // Attempt select query execution
                    $sql = "SELECT * from subjects";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="data-table table stripe hover nowrap">';
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

                                            echo '<a href="#" class="m-2 edit-btn" data-toggle="modal" data-target="#editModal'.$row['subject_id'].'" title="Edit Record" data-toggle="tooltip"><span class="bi bi-pencil-fill mr-2" style="font-size: 18px;"></span></a>';
                                            
                                            // Edit Modal
                                            echo '
                                            <div class="modal fade" id="editModal'.$row['subject_id'].'" tabindex="-1" aria-labelledby="editModalLabel'.$row['subject_id'].'" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel'.$row['subject_id'].'">Edit Subject</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

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
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                    </form>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';                                            
                                            echo '<a href="#" data-toggle="modal" data-target="#deleteModal'.$row['subject_id'].'" title="Delete Record" data-toggle="tooltip">
                                            <span class="bi bi-trash-fill" style="font-size: 18px;"></span>
                                          </a>';
                                    

                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['subject_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['subject_id'].'" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['subject_id'].'">Confirm Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to delete this subject?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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

                  <!-- Modal -->
                  <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

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
