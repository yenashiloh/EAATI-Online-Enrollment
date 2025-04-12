<?php

include 'config.php';

session_start();

$accounting_id = $_SESSION['accounting_id'];

if(!isset($accounting_id)){
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
    <link rel="apple-touch-icon" sizes="180x180" href="../asset/img/logo.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../asset/img/logo.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../asset/img/logo.png" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="../vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
        crossorigin="anonymous"></script>
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
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            "gtm.start": new Date().getTime(),
            event: "gtm.js"
        });
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
					// Count pending cash payments
					$queryCash = "SELECT COUNT(*) AS pending_cash FROM transactions WHERE status = 2 AND payment_method = 'Cash'";
					$resultCash = $link->query($queryCash);
					$pending_cash = ($resultCash && $resultCash->num_rows > 0) ? $resultCash->fetch_assoc()["pending_cash"] : 0;
					
					// Count pending installment payments
					$queryInstallment = "SELECT COUNT(*) AS pending_installment FROM transactions WHERE status = 2 AND payment_method = 'Installment'";
					$resultInstallment = $link->query($queryInstallment);
					$pending_installment = ($resultInstallment && $resultInstallment->num_rows > 0) ? $resultInstallment->fetch_assoc()["pending_installment"] : 0;
					
					// Count total students
					$queryStudents = "SELECT COUNT(*) AS total_students FROM users WHERE role = 'student'";
					$resultStudents = $link->query($queryStudents);
					$total_students = ($resultStudents && $resultStudents->num_rows > 0) ? $resultStudents->fetch_assoc()["total_students"] : 0;
					?>

                <!-- Students Card -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo $total_students; ?></div>
                                <div class="font-14 text-secondary weight-500">Students</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#FFEB3B">
                                    <span class="icon-copy dw dw-group"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Cash Payment Card -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo $pending_cash; ?></div>
                                <div class="font-14 text-secondary weight-500">Pending Cash Payment</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#00eccf">
                                    <span class="icon-copy dw dw-money"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Installment Payment Card -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo $pending_installment; ?></div>
                                <div class="font-14 text-secondary weight-500">Pending Installment Payment</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#ff5b5b">
                                    <span class="icon-copy dw dw-credit-card"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-blue h4 mb-0">Payment</h4>
                    <a href="payment.php" class="btn btn-primary">View</a>
                </div>

                <?php
					if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
						echo "<div class='alert alert-success mb-20'>Payment Deleted Successfully!</div>";
					}
					?>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Grade Level</th>
                                <th>Upon Enrollment</th>
                                <th>Total Tuition Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
								require_once "config1.php";
								$sql = "SELECT * FROM payments INNER JOIN gradelevel ON payments.grade_level = gradelevel.gradelevel_id";
								if($result = mysqli_query($link, $sql)){
									if(mysqli_num_rows($result) > 0){
										$counter = 1;
										while($row = mysqli_fetch_array($result)){
											echo "<tr>";
											echo "<td>" . $counter++ . "</td>";
											echo "<td>" . $row['gradelevel_name'] . "</td>";
											echo "<td>" . number_format($row['upon_enrollment'], 2) . "</td>";
											echo "<td>" . number_format($row['total_whole_year'], 2) . "</td>";
											echo "</tr>";
										}
										mysqli_free_result($result);
									} else {
										echo '<tr><td colspan="4" class="text-center"><div class="alert alert-danger"><em>No records were found.</em></div></td></tr>';
									}
								} else {
									echo '<tr><td colspan="4" class="text-center">Oops! Something went wrong. Please try again later.</td></tr>';
								}
								mysqli_close($link);
								?>
                        </tbody>
                    </table>
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
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
</body>

</html>