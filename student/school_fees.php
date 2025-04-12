<?php
include 'config.php';

session_start();
error_reporting(E_ALL);

if (!isset($_SESSION['student_id'])) {
    header('location:login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$error = "";
$msg = "";

$grade_level_id = null;
$sql = "SELECT grade_level_id FROM student WHERE userId = :student_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':student_id', $student_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $grade_level_id = $row['grade_level_id'];
}

$grade_number = null;
if ($grade_level_id) {
    $sql = "SELECT gradelevel_name FROM gradelevel WHERE gradelevel_id = :grade_level_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':grade_level_id', $grade_level_id);
    $stmt->execute();
    $grade_row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($grade_row) {
        preg_match('/(\d+)/', $grade_row['gradelevel_name'], $matches);
        if (isset($matches[1])) {
            $grade_number = (int)$matches[1];
        }
    }
}

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>School Fees</title>

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
									<h4>School Fees</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="student_dashboard.php">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											School Fees
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
            
                    <h3 class="text-center mb-2 fw-bold">STATEMENT OF ACCOUNTS</h3>
                    <h6 class="text-center mb-4">INSTALLMENT/MONTHLY BASIS</h6>

                <div class="row">
                    <?php if ($grade_number == 1): ?>
                        <!-- Grade 1 -->
                        <div class="container">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-md-8 mb-4">
                                    <div class="card shadow-sm mx-auto">
                                    <div class="card-header text-center">GRADE 1</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">8,925</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">18,275</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,628</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱25,775</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    <?php elseif ($grade_number == 2): ?>
                        <!-- Grade 2 -->
                        <div class="container">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-md-8 mb-4">
                                    <div class="card shadow-sm mx-auto">
                                    <div class="card-header text-center">GRADE 2</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">9,450</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">18,800</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,680</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱26,300</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($grade_number == 3): ?>
                        <!-- Grade 3 -->
                        <div class="container">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-md-8 mb-4">
                                    <div class="card shadow-sm mx-auto">
                                        <div class="card-header text-center fw-bold">GRADE 3</div>
                                        <div class="card-body">
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th>Registration Fee</th>
                                                        <td class="text-end">2,000</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tuition Fee (10 Months)</th>
                                                        <td class="text-end">9,975</td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Miscellaneous Fee
                                                            <ul class="list-unstyled ms-3">
                                                                <li>• ID / Test Paper</li>
                                                                <li>• Card</li>
                                                                <li>• Developmental</li>
                                                                <li>• Computer Basic</li>
                                                                <li>• Laboratory / Library</li>
                                                            </ul>
                                                        </th>
                                                        <td class="text-end align-middle">7,350</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Fee</th>
                                                        <td class="text-end">19,325</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                        <td class="text-end">1,733</td>
                                                    </tr>
                                                    <tr>
                                                        <th>BOOKS</th>
                                                        <td class="text-end">7,500</td>
                                                    </tr>
                                                    <tr class=" fw-bold">
                                                        <th>TOTAL FEE</th>
                                                        <td class="text-end">₱26,825</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($grade_number == 4): ?>
                        <!-- Grade 4 -->
                        <div class="container">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-md-8 mb-4">
                                    <div class="card shadow-sm mx-auto">
                                    <div class="card-header text-center">GRADE 4</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">10,290</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">19,640</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,764</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱27,140</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($grade_number == 5): ?>
                        <!-- Grade 5 -->
                        <div class="container">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-md-8 mb-4">
                                    <div class="card shadow-sm mx-auto">
                                    <div class="card-header text-center">GRADE 5</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">10,290</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">19,640</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,764</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱27,140</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php elseif ($grade_number == 6): ?>
                        <!-- Grade 6 -->
                       <div class="container">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-md-8 mb-4">
                                    <div class="card shadow-sm mx-auto">
                                    <div class="card-header text-center">GRADE 6</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">10,290</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">19,640</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,764</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱27,140</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($grade_number >= 7 && $grade_number <= 10): ?>
                        <!-- Grade 7-10 -->
                        <div class="container">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-md-8 mb-4">
                                    <div class="card shadow-sm mx-auto">
                                    <div class="card-header text-center text-uppercase"><?php echo "Grade " . $grade_number; ?></div>

                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000.00</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">5,000.00</td>
                                                </tr>

                                                <tr>
                                                    <th>School ID</th>
                                                    <td class="text-end">350.00</td>
                                                </tr>
                                                <tr>
                                                    <th>Books (1 Set)</th>
                                                    <td class="text-end">7,850.00</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Tuitions (10 Months)</th>
                                                    <td class="text-end">1,180.00</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱27,000.00</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-center text-danger"><strong>FREE: Book Bag Only</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-12">
                    <div class="alert alert-warning text-center">
                        No fee structure is available for your grade level at the moment.
                    </div>
                </div>
            <?php endif; ?>
                </div>

			</div>
		</div>

		<!-- js -->
        <?php
            include 'footer.php';
        ?>
		
	</body>
</html>