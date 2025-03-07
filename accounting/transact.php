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
		<title>Transact</title>

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
									<h4>Transact</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="accounting_dashboard.php">Dashboard</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Transact
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
						<div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                        <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#addTransactionModal">
                            <i class="fa fa-plus"></i> Add Transaction
                        </button>
							</a>

                            <div class="pb-20">
                                <?php
                                require_once "config1.php";

                                $sql = "SELECT transactions.*, student.name, student.isPaidUpon
                                        FROM transactions
                                        LEFT JOIN student ON transactions.user_id = student.userId";

                                if ($result = mysqli_query($link, $sql)) {
                                    if (mysqli_num_rows($result) > 0) {
                                        echo '<table class="data-table table stripe hover nowrap">';
                                        echo "<thead>";
                                        echo "<tr>";
                                        echo "<th>No.</th>";
                                        echo "<th>Student Name</th>";
                                        echo "<th>Reference No.</th>";
                                        echo "<th>Payment Mode</th>";
                                        echo "<th>Payment Amount</th>";
                                        echo "<th>Balance</th>";
                                        echo "<th>Payment Date</th>";
                                        echo "<th>Status</th>";
                                        echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";

                                        $count = 1; // Initialize counter
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $count . "</td>"; // Display counter
                                            echo "<td>" . (isset($row['name']) ? $row['name'] : 'N/A') . "</td>";
                                            echo "<td>" . htmlspecialchars($row['reference_number']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['payment_method']);
                                            if ($row["payment_method"] == 'GCash') {
                                                echo '<a href="' . htmlspecialchars($row['screenshot_path']) . '" target="_blank"> - View Screenshot</a>';
                                            }
                                            echo "</td>";
                                            echo "<td>" . '₱' . htmlspecialchars($row["payment_amount"]) . "</td>";
                                            echo "<td>" . '₱' . htmlspecialchars($row["balance"]) . "</td>";
                                            echo "<td>" . date("F j, Y", strtotime($row["created_at"])) . "</td>";

                                            echo "<td>";
                                            if ($row["status"] == 0) {
                                                echo '<span class="badge bg-warning text-dark">Not yet verified</span>';
                                            } else if ($row["status"] == 1) {
                                                echo '<span class="badge bg-success text-dark">Verified</span>';
                                            } else if ($row["status"] == 2) {
                                                echo '<span class="badge bg-warning text-dark">Paid</span>';
                                            } else {
                                                echo '<span class="badge bg-danger text-dark">Rejected</span>';
                                            }
                                            echo "</td>";
                                            echo "</tr>";

                                            $count++; // Increment counter
                                        }
                                        echo "</tbody>";
                                        echo "</table>";

                                        // Free result set
                                        mysqli_free_result($result);
                                    } else {
                                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                    }
                                } else {
                                    echo "Oops! Something went wrong. Please try again later.";
                                }

                                // Close connection
                                mysqli_close($link);
                                ?>
                            </div>
                        </div>
        	        </div>
		        </div>
	        </div>
        </div>

            <!-- Add Transaction Modal -->
        <div class="modal fade" id="addTransactionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myLargeModalLabel">Add Transaction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form action="add_transaction.php" method="post">
                            <div class="mb-3">
                                <label for="studentName" class="form-label">Student Name</label>
                                <select class="custom-select col-12" id="studentName" name="student_id" required>
                                    <?php
                                    include "config1.php";
                                    $sql = "SELECT * FROM student";
                                    if ($result = mysqli_query($link, $sql)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo '<option value="' . $row['student_id'] . '" data-user-id="' . $row['userId'] . '">' . $row['name'] . '</option>';
                                            }
                                            mysqli_free_result($result);
                                        } else {
                                            echo '<option value="">No students found</option>';
                                        }
                                    } else {
                                        echo '<option value="">Oops! Something went wrong.</option>';
                                    }
                                    mysqli_close($link);
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="referenceNumber" class="form-label">Reference Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="referenceNumber" name="reference_number" required>
                                    <button type="button" class="btn btn-secondary" id="generateReferenceNumber">Generate</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Payment Mode</label>
                                <select class="custom-select col-12" id="paymentMethod" name="payment_method">
                                    <option value="Cash">Cash</option>
                                    <option value="Installment">Installment</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paymentAmount" class="form-label">Payment Amount</label>
                                <input type="number" class="form-control" id="paymentAmount" name="payment_amount" value="0.0" required>
                            </div>
                            <div class="mb-3">
                                <label for="balance" class="form-label">Balance</label>
                                <input type="number" class="form-control" id="balance" name="balance" value="0.0" required>
                            </div>
                            <div class="mb-3">
                                <label for="paymentDate" class="form-label">Payment Date</label>
                                <input type="date" class="form-control" id="paymentDate" name="payment_date" required>
                            </div>
                            <div class="mb-3" id="isPaidRadioDiv" style="display: none;">
                                <label for="isPaidRadio" class="form-label">Installment or Not?</label>
                                <div>
                                    <input type="radio" id="isPaidYes" name="is_paid" value="1">
                                    <label for="isPaidYes">Yes</label>
                                    <input type="radio" id="isPaidNo" name="is_paid" value="0">
                                    <label for="isPaidNo">No</label>
                                </div>
                            </div>
                            <div class="modal-footer px-0 pb-0">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
         		<!-- js -->
        <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const studentSelect = document.getElementById('studentName');
            const isPaidRadioDiv = document.getElementById('isPaidRadioDiv');

            studentSelect.addEventListener('change', function() {
                const selectedOption = studentSelect.options[studentSelect.selectedIndex];
                const isPaidUpon = selectedOption.getAttribute('data-is-paid-upon');
                if (isPaidUpon === '0') {
                    isPaidRadioDiv.style.display = 'block';
                } else {
                    isPaidRadioDiv.style.display = 'none';
                }
            });
        });
    </script>
    
    <script>
        $(document).ready(function() {
            $('#generateReferenceNumber').click(function() {
                // Generate a random reference number with 10 digits
                var referenceNumber = '';
                for (var i = 0; i < 10; i++) {
                    referenceNumber += Math.floor(Math.random() * 10); // Generate a random digit (0-9) and concatenate to the referenceNumber
                }
                $('#referenceNumber').val(referenceNumber);
            });
        });
    </script>
	</body>
</html>
