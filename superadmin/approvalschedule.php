<?php
include 'config.php';
session_start();

if (!isset($_SESSION['superadmin_id'])) {
    header('location: ../login.php');
    exit;
}

$superadmin_id = $_SESSION['superadmin_id'];
$message = "";
$alert_class = "";

// Define the same grade groups as in your display page
$gradeGroups = [
    '1-3' => ['Grade 1', 'Grade 2', 'Grade 3'],
    '4-6' => ['Grade 4', 'Grade 5', 'Grade 6'],
    '7-8' => ['Grade 7', 'Grade 8'],
    '9-10' => ['Grade 9', 'Grade 10']
];

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $enrollmentschedule_id = intval($_GET['id']); // Ensure it's an integer

    if ($action == 'approve') {
        $status = 'Approved';
    } elseif ($action == 'decline') {
        $status = 'Declined';
    }

    if (isset($status)) {
        try {
            $conn->beginTransaction();

            // Fetch the grade level name for the given enrollmentschedule_id
            $sql = "SELECT gl.gradelevel_name 
                    FROM enrollmentschedule es
                    JOIN gradelevel gl ON es.gradelevel_id = gl.gradelevel_id
                    WHERE es.enrollmentschedule_id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $enrollmentschedule_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                throw new Exception("Schedule not found.");
            }

            $gradeName = $row['gradelevel_name'];
            
            // Find which group this grade belongs to
            $targetGroup = null;
            foreach ($gradeGroups as $groupName => $grades) {
                if (in_array($gradeName, $grades)) {
                    $targetGroup = $grades;
                    break;
                }
            }
            
            if (empty($targetGroup)) {
                throw new Exception("Grade group not found for $gradeName.");
            }

            // Get all gradelevel_ids in the same group
            $placeholders = str_repeat('?,', count($targetGroup) - 1) . '?';
            $gradeIdSql = "SELECT gradelevel_id FROM gradelevel WHERE gradelevel_name IN ($placeholders)";
            $gradeIdStmt = $conn->prepare($gradeIdSql);
            
            // Bind the grade names as parameters
            foreach ($targetGroup as $index => $grade) {
                $gradeIdStmt->bindValue($index + 1, $grade);
            }
            
            $gradeIdStmt->execute();
            $gradeIds = $gradeIdStmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($gradeIds)) {
                throw new Exception("No matching grade levels found in database.");
            }

            // Update all schedules in the group
            $idPlaceholders = str_repeat('?,', count($gradeIds) - 1) . '?';
            $updateSql = "UPDATE enrollmentschedule SET status = ? WHERE gradelevel_id IN ($idPlaceholders)";
            $updateStmt = $conn->prepare($updateSql);
            
            // Bind the status as the first parameter
            $updateStmt->bindValue(1, $status);
            
            // Bind the grade IDs as the remaining parameters
            foreach ($gradeIds as $index => $id) {
                $updateStmt->bindValue($index + 2, $id);
            }

            if ($updateStmt->execute()) {
                $conn->commit();
                $message = "Schedule status updated to $status for all grades in the group!";
                $alert_class = "alert-success";
            } else {
                throw new Exception("Failed to update schedules.");
            }
        } catch (Exception $e) {
            $conn->rollBack();
            $message = "Error: " . $e->getMessage();
            $alert_class = "alert-danger";
        }
    }
}

// Display message inside the view
if (!empty($message)) {
    echo "<div class='alert $alert_class'>$message</div>";
}
?>



<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>User</title>

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
									<h4>User</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="superadmin_dashboard.php">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Schedule of Approval
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
					<div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
							
					<div class="pd-20">
                    <h4 class="h4 mb-2">Enrollment Schedule</h4>
						</div>

                        <div class="pb-20">
                            <?php if (isset($message)): ?>
                            <div class="alert <?php echo $alert_class; ?>" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

						<?php
						include_once 'config1.php';

						$gradeGroups = [
							'1-3' => ['Grade 1', 'Grade 2', 'Grade 3'],
							'4-6' => ['Grade 4', 'Grade 5', 'Grade 6'],
							'7-8' => ['Grade 7', 'Grade 8'],
							'9-10' => ['Grade 9', 'Grade 10']
						];

						echo '<table class="table table-striped">
							<thead>
								<tr>
								<th>Grade Group</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Status</th>
								<th>Actions</th>
								</tr>
							</thead>
							<tbody>';

						foreach ($gradeGroups as $groupName => $grades) {
							echo '<tr>';
							echo '<td>Grades ' . htmlspecialchars($groupName) . '</td>';

							$gradeNamesStr = "'" . implode("', '", $grades) . "'";
							$sql = "SELECT es.enrollmentschedule_id, gl.gradelevel_name, es.start_date, es.end_date, es.status 
									FROM enrollmentschedule es
									JOIN gradelevel gl ON es.gradelevel_id = gl.gradelevel_id
									WHERE gl.gradelevel_name IN ($gradeNamesStr)
									GROUP BY gl.gradelevel_name";

							$result = mysqli_query($link, $sql);

							if (mysqli_num_rows($result) > 0) {
								$firstRow = mysqli_fetch_assoc($result);
								$allSame = true;
								$startDate = $firstRow['start_date'];
								$endDate = $firstRow['end_date'];
								$status = $firstRow['status'];

								mysqli_data_seek($result, 0);
								while ($row = mysqli_fetch_assoc($result)) {
									if ($row['start_date'] != $startDate || $row['end_date'] != $endDate || $row['status'] != $status) {
										$allSame = false;
										break;
									}
								}

								if ($allSame) {
									echo '<td>' . date('M d, Y', strtotime($startDate)) . '</td>';
									echo '<td>' . date('M d, Y', strtotime($endDate)) . '</td>';
									echo '<td>' . htmlspecialchars($status) . '</td>';
									echo '<td>';
									
									if ($status == 'For Review') {
										echo '<a href="approvalschedule.php?action=approve&id=' . $firstRow['enrollmentschedule_id'] . '" class="m-2" title="Approve Record"><span class="bi bi-check-circle-fill"></span></a>';
										echo '<a href="approvalschedule.php?action=decline&id=' . $firstRow['enrollmentschedule_id'] . '" class="m-2" title="Decline Record"><span class="bi bi-x-circle-fill"></span></a>';
									} elseif ($status == 'Approved') {
										echo '<a href="approvalschedule.php?action=decline&id=' . $firstRow['enrollmentschedule_id'] . '" class="m-2" title="Decline Record"><span class="bi bi-x-circle-fill"></span></a>';
									} elseif ($status == 'Declined') {
										echo '<a href="approvalschedule.php?action=approve&id=' . $firstRow['enrollmentschedule_id'] . '" class="m-2" title="Approve Record"><span class="bi bi-check-circle-fill"></span></a>';
									}

									echo '</td>';
								}
							} else {
								echo '<td colspan="3"><span class="badge badge-secondary">Not Scheduled</span></td>';
								echo '<td></td>';
							}

							echo '</tr>';
						}

						echo '</tbody></table>';
						?>

                    </div>
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
