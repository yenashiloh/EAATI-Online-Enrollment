<?php
include 'config.php';

session_start();

	if (!isset($_SESSION['student_id'])) {
		header('location:login.php');
		exit;
	}

	$user_id = $_SESSION['student_id']; 

	$query = "SELECT student_id FROM student WHERE userid = :userid";
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':userid', $user_id, PDO::PARAM_INT);
	$stmt->execute();

	if ($stmt->rowCount() > 0) {
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$actual_student_id = $row['student_id'];
	} else {
		$actual_student_id = 0;
	}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Grade</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../asset/img/logo.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../asset/img/logo.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../asset/img/logo.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="../vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="src/plugins/cropperjs/dist/cropper.css" />
    <link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258" crossorigin="anonymous"></script>
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

    <main id="main" class="main">
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
							<div class="col-md-12">
                                    <a href="view_card.php?id=<?php echo $actual_student_id; ?>" class="btn btn-primary btn-lg mr-3">View Grade</a>
                                    <a href="view_card.php?id=<?php echo $actual_student_id; ?>&print=true" class="btn btn-secondary btn-lg">Print Grade</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php
    include 'footer.php';
    ?>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/chart.js/chart.umd.js"></script>
    <script src="../assets/vendor/echarts/echarts.min.js"></script>
    <script src="../assets/vendor/quill/quill.min.js"></script>
    <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="../assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>
  
</body>
</html>