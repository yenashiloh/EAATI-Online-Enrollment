<?php

include 'config.php';

session_start();

$teacher_id = $_SESSION['teacher_id'];

if(!isset($teacher_id)){
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

        <!-- Site favicon -->
        <link
			rel="apple-touch-icon"
			sizes="180x180"
			href="../asset/img/logo.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="../asset/img/logo.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="../asset/img/logo.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="../vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->

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
											<a href="teacher_dashboard.php">Menu</a>
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
					
						<div class="pd-20">
                        <h4 class="h4 mb-1">Schedule List</h4>
						</div>

                        <?php
                            if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
                                echo "<div class='alert alert-success'>Record Deleted Successfully!</div>";
                            }
                        ?>
                    <div class="pb-20">
                        <?php
                            // Include config file
                            require_once "config1.php";

                            // Set the timezone to Asia/Manila
                            date_default_timezone_set('Asia/Manila');

                            // Attempt select query execution
                            $sql = "SELECT schedules.*, subjects.subject_name, sections.section_name, rooms.room_name, CONCAT(users.first_name, ' ', users.last_name) AS teacher_name FROM schedules 
                            INNER JOIN users ON schedules.teacher_id = users.id
                            INNER JOIN sections on sections.section_id = schedules.section_id
                            INNER JOIN subjects ON subjects.subject_id = schedules.subject_id
                            INNER JOIN gradelevel ON gradelevel.gradelevel_id = schedules.grade_level
                            INNER JOIN rooms ON rooms.room_id = schedules.room_id
                            WHERE schedules.teacher_id = $teacher_id";
                            
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) > 0){
                                    echo '<table class="data-table table stripe hover nowrap">';
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>No.</th>";
                                                echo "<th>Grade Level and Section</th>";
                                                echo "<th>Room</th>";
                                                echo "<th>Subject Name</th>";
                                                echo "<th>Teacher</th>";
                                                echo "<th>Start Time</th>";
                                                echo "<th>End Time</th>";
                                                echo "<th>Action</th>";
                                            echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        $counter = 1; 

                                        while($row = mysqli_fetch_array($result)){
                                            $start_time = new DateTime($row['start_time']);
                                            $start_time->setTimezone(new DateTimeZone('Asia/Manila'));
                                            $formatted_start_time = $start_time->format('h:i A'); 

                                            $end_time = new DateTime($row['end_time']);
                                            $end_time->setTimezone(new DateTimeZone('Asia/Manila'));
                                            $formatted_end_time = $end_time->format('h:i A'); 

                                            echo "<tr>";
                                                echo "<td>" . $counter++ . "</td>"; 
                                                echo "<td>" . "Grade " . $row['grade_level'] . " - " . $row['section_name'] . "</td>";
                                                echo "<td>" . $row['room_name'] . "</td>";
                                                echo "<td>" . $row['subject_name'] . "</td>";
                                                echo "<td>" . ucwords($row['teacher_name']) . "</td>";

                                                echo "<td>" . $formatted_start_time . "</td>";
                                                echo "<td>" . $formatted_end_time . "</td>";
												echo "<td>";
												// echo '<a href="edit_schedule.php?id=' . $row['id'] . '" class="btn p-1 me-1" title="Update Record">
												// 		<span class="bi bi-pencil-fill" style="font-size: 18px;"></span>
												// 	  </a>';
												// echo '<a href="#" class="btn p-1 me-1" data-toggle="modal" data-target="#deleteModal' . $row['id'] . '" title="Delete Record">
												// 		<span class="bi bi-trash-fill"  style="font-size: 18px;"></span>
												// 	  </a>';
												
												// Delete Modal
												echo '
												<div class="modal fade" id="deleteModal' . $row['id'] . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row['id'] . '" aria-hidden="true">
													<div class="modal-dialog modal-dialog-centered">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="deleteModalLabel' . $row['id'] . '">Confirm Delete</h5>
											       				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
															</div>
															<div class="modal-body">
																Are you sure you want to delete this schedule?
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
																<a href="delete.php?id=' . $row['id'] . '" class="btn btn-danger">Delete</a>
															</div>
														</div>
													</div>
												</div>';
												echo '<a href="encode_student.php?id=' . $row['id'] . '" class="btn p-0" title="Add Student">
														<span class="bi bi-plus-square-fill"  style="font-size: 18px;"></span>
													  </a>';
												
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

		<!-- js -->
    	<script src="../vendors/scripts/core.js"></script>
		<script src="../vendors/scripts/script.min.js"></script>
		<script src="../vendors/scripts/process.js"></script>
		<script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<!-- buttons for Export datatable -->
		<script src="../src/plugins/datatables/js/dataTables.buttons.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.print.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.html5.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.flash.min.js"></script>
		<script src="../src/plugins/datatables/js/pdfmake.min.js"></script>
		<script src="../src/plugins/datatables/js/vfs_fonts.js"></script>
		<!-- Datatable Setting js -->
		<script src="../vendors/scripts/datatable-setting.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
