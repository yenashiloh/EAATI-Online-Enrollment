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
		<title>Schedule</title>

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
									<h4>Schedule</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="registrar_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Schedule
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
                            echo "<div class='alert alert-success'>Schedule Deleted Successfully!</div>";
                        }
                        ?>

                        <a href="create_schedule.php" class="btn btn-success mb-4"><i class="fa fa-plus"></i> Add Schedule</a>
                        
                        <div class="pb-20">
                        <?php
                            require_once "config1.php";

                            // Query to get schedule data
                            $sql = "SELECT schedules.*, subjects.subject_name, sections.section_name, rooms.room_name, gradelevel.gradelevel_name, 
                                    CONCAT(users.first_name, ' ', users.last_name) AS teacher_name 
                                    FROM schedules 
                                    INNER JOIN users ON schedules.teacher_id = users.id
                                    INNER JOIN sections ON sections.section_id = schedules.section_id
                                    INNER JOIN subjects ON subjects.subject_id = schedules.subject_id
                                    INNER JOIN gradelevel ON gradelevel.gradelevel_id = schedules.grade_level
                                    INNER JOIN rooms ON rooms.room_id = schedules.room_id
                                    ORDER BY gradelevel_name ASC";

                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    echo '<table class="data-table table stripe hover nowrap">';
                                    echo "<thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Grade Level and Section</th>
                                                <th>Room</th>
                                                <th>Subject Name</th>
                                                <th>Teacher</th>
                                                <th>Day</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>";
                                    echo "<tbody>";

                                    $counter = 1;
                                    while ($row = mysqli_fetch_array($result)) {
                                        // Set timezone to Asia/Manila
                                        date_default_timezone_set('Asia/Manila');

                                        // Check if the time is in the correct format
                                        $startTime = DateTime::createFromFormat('H:i:s', $row['start_time']);
                                        $endTime = DateTime::createFromFormat('H:i:s', $row['end_time']);

                                        if ($startTime && $endTime) {
                                            $formattedStartTime = $startTime->format('g:i A'); // Example: 8:00 AM
                                            $formattedEndTime = $endTime->format('g:i A'); 
                                        } else {
                                            $formattedStartTime = "N/A";
                                            $formattedEndTime = "N/A";
                                        }

                                        $teacherName = ucwords(strtolower($row['teacher_name']));

                                        echo "<tr>
                                                <td>{$counter}</td>
                                                <td>{$row['gradelevel_name']} - {$row['section_name']}</td>
                                                <td>{$row['room_name']}</td>
                                                <td>{$row['subject_name']}</td>
                                                <td>{$teacherName}</td>
                                                <td>{$row['day']}</td>
                                                <td>{$formattedStartTime}</td>
                                                <td>{$formattedEndTime}</td>
                                                <td>
                                                    <a href='edit_schedule.php?id={$row['id']}' class='m-2' title='Update Record' data-toggle='tooltip'>
                                                        <span class='bi bi-pencil-fill' style='font-size: 18px;'></span>
                                                    </a>
                                                    <a href='#' class='r-2' data-toggle='modal' data-target='#deleteModal{$row['id']}' title='Delete Record'>
                                                        <span class='bi bi-trash-fill' style='font-size: 18px;'></span>
                                                    </a>
                                                    <a href='encode_student.php?id={$row['id']}' class='m-2' title='Add Student' data-toggle='tooltip'>
                                                        <span class='bi bi-plus-square-fill' style='font-size: 18px;'></span>
                                                    </a>
                                                </td>
                                            </tr>";

                                        // Delete Modal
                                        echo "<div class='modal fade' id='deleteModal{$row['id']}' tabindex='-1' aria-labelledby='deleteModalLabel{$row['id']}' aria-hidden='true'>
                                                <div class='modal-dialog modal-dialog-centered'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h5 class='modal-title' id='deleteModalLabel{$row['id']}'>Confirm Delete</h5>
                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                        </div>
                                                        <div class='modal-body'>
                                                            Are you sure you want to delete this schedule?
                                                        </div>
                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                                                            <a href='delete.php?id={$row['id']}' class='btn btn-danger'>Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>";
                                        
                                        $counter++; 
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
