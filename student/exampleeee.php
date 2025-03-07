<?php
session_start(); 
include 'config.php';
$student_id = $_SESSION['student_id'];
if (!isset($student_id)) {
  header('location:login.php');
  exit();
}

// Check if grades exist for the student
try {
    $check_grades = "SELECT COUNT(*) as count FROM encodedgrades WHERE student_id = :student_id";
    $stmt = $conn->prepare($check_grades);
    $stmt->execute(['student_id' => $student_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $has_grades = $result['count'] > 0;
} catch(PDOException $e) {
    // Handle any database errors
    $has_grades = false;
    error_log("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Grade</title>

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
									<h4>Grade</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="student_dashboard.php">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Grade
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
          <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary btn-lg mr-3" 
                                <?php if ($has_grades): ?>
                                    onclick="window.location.href='view_card.php?id=<?= $student_id ?>'"
                                <?php else: ?>
                                    data-toggle="modal" data-target="#noGradesModal"
                                <?php endif; ?>>
                                View Grade
                            </button>

                            <button type="button" class="btn btn-secondary btn-lg" 
                                <?php if ($has_grades): ?>
                                    onclick="printViewCard()"
                                <?php else: ?>
                                    data-toggle="modal" data-target="#noGradesModal"
                                <?php endif; ?>>
                                Download Grade
                            </button>

                            </div>
                        </div>
                    </div>
                </div>

      
                <div class="modal fade" id="noGradesModal" tabindex="-1" aria-labelledby="noGradesModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myLargeModalLabel"  id="noGradesModalLabel">
                                                  No Grades Available
                                                </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                    ×
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>There are currently no grades available for viewing or downloading.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

            </div>
        </div>
    </div>
			</div>
		</div>
    <?php
            include 'footer.php';
        ?>
		<!-- js -->

    
    <script>
    function printViewCard() {
        <?php if($has_grades): ?>
        window.open('view_card.php?id=<?php echo $student_id; ?>', '_blank');
        <?php endif; ?>
    }
    </script>
	</body>
</html>
