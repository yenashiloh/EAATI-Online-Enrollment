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
		<title>Encoded Grades</title>

       <?php
         include 'link.php';
       ?>

	</head>
	<body class="sidebar-light">
    <?php
        include 'header.php';
        include 'sidebar.php';

        // Fetch user details for editing
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM schedules WHERE id = :id";
            $query = $conn->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $student = $query->fetch(PDO::FETCH_ASSOC);
        }
    ?>

		<div class="mobile-menu-overlay"></div>
		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Encoded Student Grade</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="teacher_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item">
											<a href="verify_grade.php">Verify Grades</a>
										</li>
                                        <li class="breadcrumb-item active" aria-current="page">
											Encoded Student Grade
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <div class="pd-20">
                        <?php
                        require_once "config1.php";

                        // Check for success message in URL
                        if (isset($_GET['status_updated']) && isset($_GET['new_status'])) {
                            $new_status = htmlspecialchars($_GET['new_status']);
                            echo "<div id='alertContainer' class='container mt-3'>
                                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                        Status successfully changed to \"{$new_status}\"
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                            <span aria-hidden='true'>×</span>
                                        </button>
                                    </div>
                                </div>";
                        }

                        // Check if the section_id is provided in the URL
                        if (isset($_GET['section_id'])) {
                            $section_id = intval($_GET['section_id']);
                            
                            // Get section details
                            $section_sql = "SELECT * FROM sections WHERE section_id = $section_id";
                            $section_result = mysqli_query($link, $section_sql);
                            $section_row = mysqli_fetch_assoc($section_result);
                            
                            if ($section_row) {
                                ?>
                                <h4 class="h4 mb-4">Section <?php echo htmlspecialchars($section_row['section_name']); ?></h4>

                                <div class="pb-20">
                                    <?php
                                    $sql = "SELECT s.student_id, s.name, ess.encoded_id, ess.schedule_id, 
                                                    eg.quarter1, eg.quarter2, eg.quarter3, eg.quarter4, eg.encodedgrades_id, eg.status
                                            FROM student s
                                            INNER JOIN encodedstudentsubjects ess ON s.student_id = ess.student_id
                                            LEFT JOIN encodedgrades eg ON (s.student_id = eg.student_id AND ess.schedule_id = eg.schedule_id)
                                            WHERE ess.schedule_id IN (
                                                SELECT schedules.id FROM schedules WHERE schedules.section_id = $section_id
                                            )
                                            AND s.isVerified = 2
                                            ORDER BY s.name";

                                    if ($result = mysqli_query($link, $sql)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            echo '<table class="data-table table stripe hover nowrap">';
                                            echo "<thead><tr>
                                                    <th>No.</th>
                                                    <th>Name</th>
                                                    <th>1st Quarter</th>
                                                    <th>2nd Quarter</th>
                                                    <th>3rd Quarter</th>
                                                    <th>4th Quarter</th>
                                                    <th>Final</th>
                                                    <th>Remarks</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr></thead><tbody>";

                                            $counter = 1;
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<tr>";
                                                echo "<td>" . $counter++ . "</td>";
                                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";

                                                $hasGrades = isset($row['quarter1']) && isset($row['quarter2']) && isset($row['quarter3']) && isset($row['quarter4']);
                                                if ($hasGrades) {
                                                    $quarter1 = intval($row['quarter1']);
                                                    $quarter2 = intval($row['quarter2']);
                                                    $quarter3 = intval($row['quarter3']);
                                                    $quarter4 = intval($row['quarter4']);
                                                    $finalGrade = ($quarter1 + $quarter2 + $quarter3 + $quarter4) / 4;
                                                    $remarks = ($finalGrade < 74) ? "Failed" : "Passed";

                                                    echo "<td>$quarter1</td><td>$quarter2</td><td>$quarter3</td><td>$quarter4</td><td>" . number_format($finalGrade, 2) . "</td><td>$remarks</td>";
                                                } else {
                                                    echo "<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
                                                }

                                                $status = isset($row['status']) ? $row['status'] : 'For Review';
                                                echo "<td>" . htmlspecialchars($status) . "</td>";
                                                echo "<td>";
                                                $encodedgrades_id = isset($row['encodedgrades_id']) ? $row['encodedgrades_id'] : 0;
                                                if ($encodedgrades_id && $status == 'For Review') {
                                                    echo "<button class='btn confirm-btn p-0' data-id='$encodedgrades_id' data-status='Approved' data-bs-toggle='tooltip' data-bs-placement='top' title='Approve'>
                                                            <i class='bi bi-check-circle-fill'></i>
                                                        </button>";
                                                    echo "<button class='btn confirm-btn' data-id='$encodedgrades_id' data-status='Declined' data-bs-toggle='tooltip' data-bs-placement='top' title='Decline'>
                                                            <i class='bi bi-x-circle-fill'></i>
                                                        </button>";
                                                } else {
                                                    echo "";
                                                }
                                                echo "</td></tr>";
                                            }

                                            echo "</tbody></table>";
                                            mysqli_free_result($result);
                                        } else {
                                            echo '<div class="alert alert-danger"><em>No students enrolled in this section.</em></div>';
                                        }
                                    } else {
                                        echo "Oops! Something went wrong. Please try again later.";
                                    }
                                    ?>
                                </div>

                                <!-- Confirmation Modal -->
                                <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Action</h5>
                                                <button type="button" class="close" data-dismiss="modal">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to change the status?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button type="button" id="confirmAction" class="btn btn-primary">Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    let selectedId, selectedStatus;
                                    
                                    document.querySelectorAll('.confirm-btn').forEach(button => {
                                        button.addEventListener('click', function () {
                                            selectedId = this.getAttribute('data-id');
                                            selectedStatus = this.getAttribute('data-status');
                                            $('#confirmModal').modal('show');
                                        });
                                    });
                                    
                                    document.getElementById('confirmAction').addEventListener('click', function () {
                                        if (selectedId && selectedStatus) {
                                            fetch('update_status.php', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                                body: 'encodedgrades_id=' + encodeURIComponent(selectedId) + '&status=' + encodeURIComponent(selectedStatus)
                                            })
                                            .then(response => response.text())
                                            .then(data => {
                                                if (data.trim() === 'success') {
                                                    const urlParams = new URLSearchParams(window.location.search);
                                                    urlParams.set('status_updated', 'true');
                                                    urlParams.set('new_status', selectedStatus);
                                                    
                                                    window.location.href = window.location.pathname + '?' + urlParams.toString();
                                                } else {
                                                    alert('Error updating status: ' + data);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert('An unexpected error occurred. Please try again.');
                                            });
                                            
                                            $('#confirmModal').modal('hide');
                                        }
                                    });

                                    const alert = document.querySelector('.alert');
                                    if (alert) {
                                        setTimeout(() => {
                                            alert.classList.remove('show');
                                            setTimeout(() => alert.remove(), 150);
                                        }, 2000);
                                    }
                                });
                                </script>
                                <?php
                            } else {
                                echo "<div class='alert alert-warning'>Section not found.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Section ID is not provided in the URL.</div>";
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

	</body>
</html>
