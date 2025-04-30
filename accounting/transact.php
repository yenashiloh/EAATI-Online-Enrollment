<?php

include 'config.php';

session_start();

$accounting_id = $_SESSION['accounting_id'];

if (!isset($accounting_id)) {
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
        href="../asset/img/logo.png" />
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="../asset/img/logo.png" />
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="../asset/img/logo.png" />

    <!-- Mobile Specific Metas -->
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../vendors/styles/icon-font.min.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css" />
    <link
        rel="stylesheet"
        type="text/css"
        href="../src/plugins/datatables/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"></script>
    <script
        async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
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

                    <?php
                    require_once "config1.php";

                    $check_structure = "SHOW COLUMNS FROM transactions";
                    $structure_result = mysqli_query($link, $check_structure);
                    $id_column = "id"; // Default value

                    while ($column = mysqli_fetch_array($structure_result)) {
                        if ($column['Key'] == 'PRI') {
                            $id_column = $column['Field'];
                            break;
                        }
                    }

                    $sql = "SELECT transactions.*, student.name, student.isPaidUpon, student.grade_level_id, gradelevel.gradelevel_name
                                FROM transactions
                                LEFT JOIN student ON transactions.user_id = student.userId
                                LEFT JOIN gradelevel ON student.grade_level_id = gradelevel.gradelevel_id";

                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="data-table table stripe hover nowrap">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>No.</th>";
                            echo "<th>Payment Date</th>";
                            echo "<th>Student Name</th>";
                            echo "<th>Reference No.</th>";
                            echo "<th>Payment Mode</th>";
                            echo "<th>Payment Amount</th>";
                            echo "<th>Balance</th>";
                            echo "<th>Print</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";

                            $count = 1; // Initialize counter
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $count . "</td>"; // Display counter
                                echo "<td>" . date("F j, Y", strtotime($row["created_at"])) . "</td>";
                                echo "<td>" . (isset($row['name']) ? $row['name'] : 'N/A') . "</td>";
                                echo "<td>" . htmlspecialchars($row['reference_number']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['payment_method']);
                                if ($row["payment_method"] == 'GCash') {
                                    echo '<a href="' . htmlspecialchars($row['screenshot_path']) . '" target="_blank"> - View Screenshot</a>';
                                }
                                echo "</td>";
                                echo "<td>" . '₱' . number_format($row["payment_amount"], 2) . "</td>";
                                echo "<td>" . '₱' . number_format($row["balance"], 2) . "</td>";
                                echo "</td>";
                                echo "<td>";
                                // Use reference_number as a fallback if ID column not found or not available
                                if (isset($row[$id_column])) {
                                    echo "<a href='print_receipt.php?id=" . $row[$id_column] . "' target='_blank' class='btn btn-primary btn-sm'>Print</a>";
                                } else if (isset($row['reference_number'])) {
                                    echo "<a href='print_receipt.php?ref=" . $row['reference_number'] . "' target='_blank' class='btn btn-primary btn-sm'>Print</a>";
                                } else {
                                    echo "ID not available";
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

  <!-- Add Transaction Modal -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Add Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                    <form action="add_transaction.php" method="post">
                        <div class="mb-3">
                            <label for="gradeLevel" class="form-label">Grade Level</label>
                            <select class="custom-select col-12" id="gradeLevel" name="grade_level_id" required>
                                <option value="">Select Grade Level</option>
                                <?php
                                include "config1.php";
                                $grades = mysqli_query($link, "SELECT * FROM gradelevel");
                                while ($grade = mysqli_fetch_assoc($grades)) {
                                    echo '<option value="' . $grade['gradelevel_id'] . '">' . $grade['gradelevel_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="studentName" class="form-label">Student Name</label>
                            <select class="custom-select col-12" id="studentName" name="student_id" required>
                                <option value="">Select Student</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Payment Mode</label>
                            <select class="custom-select col-12" id="paymentMethod" name="payment_method">
                                <option value="Cash Basis">Plan A - Cash Basis (Full Payment)</option>
                                <option value="Installment (20% DP)">Plan B - Installment (20% Down Payment)</option>
                                <option value="Installment (30% DP)">Plan C - Installment (30% Down Payment)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="paymentAmount" class="form-label">Payment Amount</label>
                            <input type="text" class="form-control" id="paymentAmount" name="payment_amount">
                            <input type="hidden" id="paymentAmountHidden" name="payment_amount_hidden">
                        </div>

                        <div class="mb-3">
                            <label for="totalTuition" class="form-label">Total Fee</label>
                            <input type="text" class="form-control" id="totalTuition" name="total_whole_year" readonly>
                            <input type="hidden" id="totalTuitionHidden" name="total_whole_year_hidden">
                        </div>

                        <div class="mb-3">
                            <label for="balance" class="form-label">Balance</label>
                            <input type="text" class="form-control" id="balance" name="balance" readonly>
                            <input type="hidden" id="balanceHidden" name="balance_hidden">
                        </div>

                        <!-- Hidden field for reference number -->
                        <input type="hidden" name="reference_number" value="">

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
    <noscript>
        <iframe
            src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
            height="0"
            width="0"
            style="display: none; visibility: hidden"></iframe>
    </noscript>
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
        // Handle grade level change
        $('#gradeLevel').change(function() {
            var gradeId = $(this).val();
            if (gradeId) {
                $.ajax({
                    type: 'POST',
                    url: 'fetch_students.php',
                    data: {
                        grade_level_id: gradeId
                    },
                    success: function(html) {
                        $('#studentName').html(html);
                        // Clear fields when grade level changes
                        clearFields();
                    }
                });
            } else {
                $('#studentName').html('<option value="">Select Student</option>');
                clearFields();
            }
        });

        // Calculate payment details based on selected student and payment method
        function calculatePayment() {
            // Get selected student option
            const selectedOption = $('#studentName option:selected');

            if (selectedOption.val() === '') {
                clearFields();
                return;
            }

            // Get grade level ID from the selected student option
            const gradeId = selectedOption.attr('data-grade');
            const studentId = selectedOption.val();

            // Fetch tuition fee data and payment history from the server
            $.ajax({
                type: 'POST',
                url: 'fetch_payment_data.php',
                data: {
                    grade_level_id: gradeId,
                    student_id: studentId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        // Display error message if no payment data found
                        alert('No payment data found for this grade level. Please set up the payment information first.');
                        clearFields();
                    } else {
                        // Set total tuition field
                        const tuitionAmount = parseFloat(response.total_whole_year);
                        $('#totalTuition').val(tuitionAmount.toFixed(2));
                        $('#totalTuitionHidden').val(tuitionAmount.toFixed(2));
                        
                        // Get total of previous payments
                        const totalPreviousPayments = parseFloat(response.total_payments) || 0;
                        
                        // Calculate remaining balance before this payment
                        const remainingBalance = tuitionAmount - totalPreviousPayments;
                        
                        // Get selected payment method
                        const paymentMethod = $('#paymentMethod').val();
                        
                        let paymentAmount = 0;
                        
                        // Calculate based on payment method
                        if (paymentMethod === 'Cash Basis') {
                            // Plan A - Full payment (remaining balance)
                            paymentAmount = remainingBalance;
                        } else if (paymentMethod === 'Installment (20% DP)') {
                            // Plan B - 20% down payment
                            const requiredDownPayment = tuitionAmount * 0.2;
                            
                            if (totalPreviousPayments >= requiredDownPayment) {
                                // If previous payments already cover the down payment,
                                // suggest the monthly installment amount or remaining balance
                                // whichever is smaller
                                const monthlyInstallment = parseFloat(response.tuition_fee);
                                paymentAmount = Math.min(monthlyInstallment, remainingBalance);
                            } else {
                                // If down payment not yet completed
                                // Pay either the remaining down payment or the remaining balance
                                // whichever is smaller
                                const remainingDownPayment = requiredDownPayment - totalPreviousPayments;
                                paymentAmount = Math.min(remainingDownPayment, remainingBalance);
                            }
                        } else if (paymentMethod === 'Installment (30% DP)') {
                            // Plan C - 30% down payment
                            const requiredDownPayment = tuitionAmount * 0.3;
                            
                            if (totalPreviousPayments >= requiredDownPayment) {
                                // If previous payments already cover the down payment,
                                // suggest the monthly installment amount or remaining balance
                                // whichever is smaller
                                const monthlyInstallment = parseFloat(response.tuition_fee);
                                paymentAmount = Math.min(monthlyInstallment, remainingBalance);
                            } else {
                                // If down payment not yet completed
                                // Pay either the remaining down payment or the remaining balance
                                // whichever is smaller
                                const remainingDownPayment = requiredDownPayment - totalPreviousPayments;
                                paymentAmount = Math.min(remainingDownPayment, remainingBalance);
                            }
                        }
                        
                        // Ensure payment amount is not negative and doesn't exceed remaining balance
                        paymentAmount = Math.max(0, Math.min(paymentAmount, remainingBalance));
                        
                        // Calculate balance after this payment
                        const balance = remainingBalance - paymentAmount;
                        
                        // Update fields with calculated values
                        $('#paymentAmount').val(paymentAmount.toFixed(2));
                        $('#paymentAmountHidden').val(paymentAmount.toFixed(2));
                        $('#balance').val(balance.toFixed(2));
                        $('#balanceHidden').val(balance.toFixed(2));
                        
                        // Generate a random reference number for the transaction
                        $('input[name="reference_number"]').val(generateReferenceNumber());
                    }
                },
                error: function() {
                    alert('Error fetching payment data. Please try again.');
                    clearFields();
                }
            });
        }
        
        // Generate a random 10-digit reference number
        function generateReferenceNumber() {
            return Math.floor(1000000000 + Math.random() * 9000000000);
        }

        // Function to clear all calculation fields
        function clearFields() {
            $('#totalTuition').val("0.00");
            $('#totalTuitionHidden').val("0.00");
            $('#paymentAmount').val("0.00");
            $('#paymentAmountHidden').val("0.00");
            $('#balance').val("0.00");
            $('#balanceHidden').val("0.00");
        }

        // Add event listeners
        $('#studentName').change(calculatePayment);
        $('#paymentMethod').change(calculatePayment);

        // Initial calculation if student is already selected
        if ($('#studentName').val()) {
            calculatePayment();
        } else {
            clearFields();
        }
    });
    </script>
</body>

</html>