<?php
session_start(); 
include 'config.php';
$student_id = $_SESSION['student_id'];
if (!isset($student_id)) {
    header('location:../login.php');
    exit();
}


?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Certificate of Registration</title>

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
			href="src/plugins/cropperjs/dist/cropper.css"
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
									<h4>Certificate of Registration</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="student_dashboard.php">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Certificate of Registration
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
			
					<div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
						<?php
						try {
							$query_transactions = "SELECT * FROM transactions WHERE user_id = :student_id AND status = 2";
							$stmt_transactions = $conn->prepare($query_transactions);
							$stmt_transactions->bindParam(':student_id', $student_id, PDO::PARAM_INT);
							$stmt_transactions->execute();

							if ($stmt_transactions->rowCount() > 0) {
								echo "<ul class='list-unstyled'>";
								echo "<li>
										<a href='cor_template.php?user_id={$student_id}' class='btn btn-primary mt-2'>
											View Certificate of Registration
										</a>
									</li>";
								echo "</ul>";
							} else {
								echo "<div class='alert alert-danger' role='alert'>
										No completed transactions found. Please proceed with payment. 
										If you've already paid and the COR is still not showing, please reach out to the administrator for assistance.
									</div>";
							}
						} catch (PDOException $e) {
							error_log("Database Error: " . $e->getMessage());
							echo "<div class='alert alert-danger' role='alert'>
									An error occurred while retrieving your Certificate of Registration. Please try again later or contact support.
								</div>";
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<!-- js -->
		<script src="../vendors/scripts/core.js"></script>
		<script src="../vendors/scripts/script.min.js"></script>
		<script src="../vendors/scripts/process.js"></script>
		<script src="../vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/cropperjs/dist/cropper.js"></script>
		
	</body>
</html>
