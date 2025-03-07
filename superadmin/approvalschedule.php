<?php
include 'config.php'; 

session_start();

$superadmin_id = $_SESSION['superadmin_id'];

if (!isset($superadmin_id)) {
    header('location:../login.php');
    exit; 
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $enrollmentschedule_id = $_GET['id'];

    if ($action == 'approve') {
        $status = 'Approved';
    } elseif ($action == 'decline') {
        $status = 'Declined';
    }

    if (isset($status)) {
        $sql = "UPDATE enrollmentschedule SET status = :status WHERE enrollmentschedule_id = :enrollmentschedule_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':enrollmentschedule_id', $enrollmentschedule_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $message = "Schedule Status Updated to $status!";
            $alert_class = "alert-success"; 
        } else {
            $message = "Error updating status: " . $stmt->errorInfo()[2];
            $alert_class = "alert-danger";
        }
    }
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
                        $sql = "SELECT enrollmentschedule.*, gradelevel.gradelevel_name 
                                FROM enrollmentschedule 
                                INNER JOIN gradelevel ON enrollmentschedule.gradelevel_id = gradelevel.gradelevel_id";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                        ?>
                            <table class="data-table table stripe hover nowrap">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Grade Level</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['enrollmentschedule_id'] . "</td>";
                                    echo "<td>" . $row['gradelevel_name'] . "</td>";
                                    echo "<td>" . $row['start_date'] . "</td>";
                                    echo "<td>" . $row['end_date'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>";

                                    if ($row['status'] == 'For Review') {
                                        echo '<a href="approvalschedule.php?action=approve&id=' . $row['enrollmentschedule_id'] . '" class="m-2" title="Approve Record" data-toggle="tooltip"><span class="bi bi-check-circle-fill"></span></a>';
                                        echo '<a href="approvalschedule.php?action=decline&id=' . $row['enrollmentschedule_id'] . '" class="m-2" title="Decline Record" data-toggle="tooltip"><span class="bi bi-x-circle-fill"></span></a>';
                                    } elseif ($row['status'] == 'Approved') {
                                        echo '<a href="approvalschedule.php?action=decline&id=' . $row['enrollmentschedule_id'] . '" class="m-2" title="Decline Record" data-toggle="tooltip"><span class="bi bi-x-circle-fill"></span></a>';
                                    } elseif ($row['status'] == 'Declined') {
                                        echo '<a href="approvalschedule.php?action=approve&id=' . $row['enrollmentschedule_id'] . '" class="m-2" title="Approve Record" data-toggle="tooltip"><span class="bi bi-check-circle-fill"></span></a>';
                                    }
                                    

                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        <?php
                        } else {
                            echo '<div class="alert alert-info">No records found.</div>';
                        }
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
