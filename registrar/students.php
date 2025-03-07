<?php
include 'config.php';
session_start();
$registrar_id = $_SESSION['registrar_id'];
if(!isset($registrar_id)){
   header('location:../login.php');
   exit; 
}

if(isset($_GET['verified']) && $_GET['verified'] == 1){
    $id = $_GET['id']; 

    $sql = "UPDATE student SET isVerified = 1 WHERE student_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $success_message = "Student Verified Successfully!";
}
    ?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Students</title>

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
									<h4>Students</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="registrar_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Students
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                        <div class="pd-20">
                            <h4 class="h4 mb-1">Students List</h4>
                        </div>

                        <div class="pb-20">
                        <?php
                        require_once "config1.php";
                        $sql = "SELECT * FROM student INNER JOIN users ON student.userId = users.id";
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table class="data-table table stripe hover nowrap">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>No.</th>"; 
                                echo "<th>Name</th>";
                                echo "<th>Date of Birth</th>";
                                echo "<th>Email</th>";
                                echo "<th>Status</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $no = 1; 
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['dob'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . ($row['isVerified'] == 1 ? 'Verified' : 'Not Verified') . "</td>";

                                    echo "<td>";
                                    echo '<a href="view_record.php?id=' . $row['student_id'] . '" title="View Student" data-toggle="tooltip">
                                            <span class="bi bi-eye-fill" style="font-size: 20px;"></span>
                                        </a>';
                                    echo '  ';

                                    if ($row['isVerified'] == 0) {
                                        echo '<a href="students.php?verified=1&id=' . $row['student_id'] . '" class="btn btn-success" title="Verify">
                                                <span class="bi bi-check-circle" style="font-size: 18px;"></span>
                                            </a>';
                                    }
                                    echo '  ';

                                    echo '<a href="#" data-toggle="modal" data-target="#Medium-modal' . $row['id'] . '" title="Delete Record" data-toggle="tooltip">
                                            <span class="bi bi-trash-fill" style="font-size: 18px;"></span>
                                        </a>';

                                    // Delete Confirmation Modal
                                    echo '
                                    <div class="modal fade" id="Medium-modal' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel' . $row['id'] . '" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this record?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <a href="delete.php?id=' . $row['id'] . '" class="btn btn-primary">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
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
