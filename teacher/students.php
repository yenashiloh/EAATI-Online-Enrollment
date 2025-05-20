<?php

include 'config.php';

session_start();

$teacher_id = $_SESSION['teacher_id'];

if (!isset($teacher_id)) {
	header('location:../login.php');
	exit;
}

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Class List</title>

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
									<h4>Class List</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="teacher_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Class List
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
					<div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
						<div class="pb-20">
							<div class="row">
								<?php
								// Include config file
								require_once "config1.php";

								// Modified query to group by grade level and section only, without subject details
								$sql = "SELECT 
									sections.section_id,
									sections.section_name,
									sections.section_description,
									sections.sectionCapacity,
									gradelevel.gradelevel_id,
									gradelevel.gradelevel_name,
									COUNT(DISTINCT encodedstudentsubjects.student_id) AS student_count
								FROM sections 
								INNER JOIN gradelevel ON gradelevel.gradelevel_id = sections.gradelevel_id
								INNER JOIN schedules ON schedules.section_id = sections.section_id
								LEFT JOIN encodedstudentsubjects ON encodedstudentsubjects.schedule_id = schedules.id
								WHERE schedules.teacher_id = '$teacher_id'
								GROUP BY sections.section_id, sections.section_name, sections.section_description, 
										 sections.sectionCapacity, gradelevel.gradelevel_id, gradelevel.gradelevel_name
								ORDER BY CAST(REPLACE(gradelevel.gradelevel_name, 'Grade ', '') AS UNSIGNED) ASC, sections.section_name ASC";

								if ($result = mysqli_query($link, $sql)) {
									if (mysqli_num_rows($result) > 0) {
										while ($row = mysqli_fetch_array($result)) {
											?>
											<div class="col-lg-3 col-md-6 mb-4">
												<div class="card">
													<div class="card-body">
														<h1 class="card-title"><?php echo $row['gradelevel_name']; ?> - <?php echo $row['section_name']; ?></h1>
														<p class="card-text">Enrolled Students: <?php echo $row['student_count']; ?></p>

														<div class="text-center">
															<a href="view_students.php?section_id=<?php echo $row['section_id']; ?>&grade_level_id=<?php echo $row['gradelevel_id']; ?>" class="btn btn-primary" title="View Students">
																<span class="bi bi-eye-fill"></span> View Students
															</a>
														</div>
													</div>
												</div>
											</div>
											<?php
										}
										// Free result set
										mysqli_free_result($result);
									} else {
										echo '<div class="col-lg-12"><div class="alert alert-danger"><em>No records were found.</em></div></div>';
									}
								} else {
									echo '<div class="col-lg-12"><div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div></div>';
								}

								// Close connection
								mysqli_close($link);
								?>
							</div> <!-- End of row -->
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
		<noscript>
			<iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
