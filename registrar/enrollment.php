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
		<title>Enrollment</title>

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
									<h4>Enrollment</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="registrar_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Enrollment
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
						<div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
					
						<div class="pd-20">
                <h4 class="h4 mb-1">Enrollment List</h4>
						</div>
                <div class="pb-20">
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
                      $sql = "SELECT * FROM student 
                              INNER JOIN users ON student.userId = users.id 
                              INNER JOIN gradelevel ON student.grade_level = gradelevel.gradelevel_id 
                              INNER JOIN approvalschedule ON student.grade_level = approvalschedule.gradelevel_id
                              WHERE approvalschedule.start_date <= CURDATE() AND approvalschedule.end_date >= CURDATE()";
                      
                      if ($result = mysqli_query($link, $sql)) {
                          if (mysqli_num_rows($result) > 0) {
                              echo '<table class="data-table table stripe hover nowrap">';
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
                              
                              while ($row = mysqli_fetch_array($result)) {
                                  echo "<tr>";
                                  echo "<td>" . $row['gradelevel_name'] . "</td>";
                                  echo "<td>" . $row['name'] . "</td>";
                                  echo "<td>" . $row['dob'] . "</td>";
                                  echo "<td>" . $row['email'] . "</td>";
                                  echo "<td>" . ($row['isVerified'] == 1 ? 'Verified' : ($row['isVerified'] == 0 ? 'Not Verified' : 'Enrolled')) . "</td>";
                                  echo "<td>";
                                  
                                  if ($row['isVerified'] == 0) {
                                      echo '<a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#verifyModal'.$row['student_id'].'" title="Verify Record" data-toggle="tooltip"><span class="bi bi-check-circle-fill"></span></a>';
                                      
                                      // Verification Modal with new design
                                      echo '
                                      <div class="modal fade" id="verifyModal'.$row['student_id'].'" tabindex="-1" aria-labelledby="verifyModalLabel'.$row['student_id'].'" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered">
                                              <div class="modal-content">
                                                  <div class="modal-header">
                                                      <h4 class="modal-title" id="myLargeModalLabel">Verification Modal</h4>
                                                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                  </div>
                                                  <div class="modal-body">
                                                      Are you sure you want to verify this record?
                                                  </div>
                                                  <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                      <a href="verify.php?id='.$row['student_id'].'" class="btn btn-primary">Save changes</a>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>';
                                  }
                                  
                                  echo '<a href="view_record.php?id='.$row['student_id'].'" class="btn btn-info" title="View Record"><span class="bi bi-eye-fill"></span></a>';
                                  echo '  ';
                                  echo '<a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['student_id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';

                                  // Delete Modal with new design
                                  echo '
                                  <div class="modal fade" id="deleteModal'.$row['student_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['student_id'].'" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <h4 class="modal-title" id="myLargeModalLabel">Confirm Delete</h4>
                                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                              </div>
                                              <div class="modal-body">
                                                  Are you sure you want to delete this record?
                                              </div>
                                              <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                  <a href="delete_enrollment.php?id='.$row['student_id'].'" class="btn btn-primary">Delete</a>
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
                          } else {
                              echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                          }
                      } else {
                          echo "Oops! Something went wrong. Please try again later.";
                      }

                      // Close connection
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


