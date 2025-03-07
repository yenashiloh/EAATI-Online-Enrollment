<?php

include 'config.php';

session_start();

$superadmin_id = $_SESSION['superadmin_id'];

if(!isset($superadmin_id)){
   header('location:../login.php');
}


?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Dashboard</title>

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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
			<div class="xs-pd-20-10 pd-ltr-20">
				<div class="title pb-20">
					<h2 class="h3 mb-0">Dashboard</h2>
				</div>

				<!-- Statistics Cards Row -->
				<div class="row pb-10">
					<?php
					include 'config1.php';
					if ($link->connect_error) {
						die("Connection failed: " . $link->connect_error);
					}
					
					// Count total students
					$queryStudents = "SELECT COUNT(*) AS total_students FROM users WHERE role = 'student'";
					$resultStudents = $link->query($queryStudents);
					$total_students = ($resultStudents && $resultStudents->num_rows > 0) ? $resultStudents->fetch_assoc()["total_students"] : 0;

					$queryStudents = "SELECT COUNT(*) AS total_teacher FROM users WHERE role = 'teacher'";
					$resultStudents = $link->query($queryStudents);
					$total_teacher = ($resultStudents && $resultStudents->num_rows > 0) ? $resultStudents->fetch_assoc()["total_teacher"] : 0;

					$queryStudents = "SELECT COUNT(*) AS total_registrar FROM users WHERE role = 'registrar'";
					$resultStudents = $link->query($queryStudents);
					$total_registrar = ($resultStudents && $resultStudents->num_rows > 0) ? $resultStudents->fetch_assoc()["total_registrar"] : 0;

					$queryStudents = "SELECT COUNT(*) AS total_accounting FROM users WHERE role = 'accounting'";
					$resultStudents = $link->query($queryStudents);
					$total_accounting = ($resultStudents && $resultStudents->num_rows > 0) ? $resultStudents->fetch_assoc()["total_accounting"] : 0;

					$queryStudents = "SELECT COUNT(*) AS total_superadmin FROM users WHERE role = 'superadmin'";
					$resultStudents = $link->query($queryStudents);
					$total_superadmin = ($resultStudents && $resultStudents->num_rows > 0) ? $resultStudents->fetch_assoc()["total_superadmin"] : 0;
					?>

					<!-- Teachers Card -->
					<div class="col-xl-4 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo $total_teacher; ?></div>
									<div class="font-14 text-secondary weight-500">Total Teachers</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#FFEB3B">
										<i class="fas fa-chalkboard-teacher"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Students Card -->
					<div class="col-xl-4 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo $total_students; ?></div>
									<div class="font-14 text-secondary weight-500">Total Students</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#ff5b5b">
										<i class="fas fa-user-graduate"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Registrar Card -->
					<div class="col-xl-4 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo $total_registrar; ?></div>
									<div class="font-14 text-secondary weight-500">Total Registrar</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#00eccf">
										<i class="fas fa-user-tie"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Accounting Card -->
					<div class="col-xl-4 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo $total_accounting; ?></div>
									<div class="font-14 text-secondary weight-500">Total Accounting</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#09cc06">
										<i class="fas fa-users"></i>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Super Admin Card -->
					<div class="col-xl-4 col-lg-3 col-md-6 mb-20">
						<div class="card-box height-100-p widget-style3">
							<div class="d-flex flex-wrap">
								<div class="widget-data">
									<div class="weight-700 font-24 text-dark"><?php echo $total_superadmin; ?></div>
									<div class="font-14 text-secondary weight-500">Total Super Admin</div>
								</div>
								<div class="widget-icon">
									<div class="icon" data-color="#FFEB3B">
										<i class="fas fa-user-shield"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Table Card - Fixed alignment -->
				<div class="row">
					<div class="col-12">
						<div class="card-box pd-20 bg-white border-radius-4 box-shadow mb-30">
							<div class="d-flex justify-content-between align-items-center mb-20">
								<h4 class="h4 mb-0">Users List</h4>
								<a href="user.php" class="btn btn-primary">
									 View All
								</a>
							</div>

							<?php
							if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
								echo "<div class='alert alert-success'>Record Deleted Successfully!</div>";
							}
							?>
							
							<div class="pb-20">
								<table class="data-table table stripe hover nowrap">
									<thead>
										<tr>
											<th>No.</th>
											<th>Name</th>
											<th>Username</th>
											<th>Role</th>
										</tr>
									</thead>
									<tbody>
									<?php
									require_once "config1.php";

									$sql = "SELECT * FROM users";
									if($result = mysqli_query($link, $sql)){
										if(mysqli_num_rows($result) > 0){
											$no = 1;
											while($row = mysqli_fetch_array($result)){
												echo "<tr>";
													echo "<td>" . $no++ . "</td>";
													echo "<td>" . ucwords($row['first_name'] . " " . $row['last_name']) . "</td>";
													echo "<td>" . $row['username'] . "</td>";
													echo "<td>" . $row['role'] . "</td>";
												echo "</tr>";
											}
										} else{
											echo '<tr><td colspan="5"><div class="alert alert-danger"><em>No records were found.</em></div></td></tr>';
										}
									} else{
										echo '<tr><td colspan="5"><div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div></td></tr>';
									}

									mysqli_free_result($result);

									mysqli_close($link);
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

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
	</body>
</html>
