<?php
include 'config.php';

session_start();
error_reporting(0);
$accounting_id = $_SESSION['accounting_id'];

if (!isset($accounting_id)) {
    header('location:../login.php');
} else {
    if (isset($_POST['add_payment'])) {
        // Database connection assumed to be established in config.php
        $grade_level = $_POST['grade_level'];
        $upon_enrollment = $_POST['upon_enrollment']; // Registration Fee
        $tuition_fee = $_POST['tuition_fee']; // Tuition Fee
        $miscellaneous_fee = $_POST['miscellaneous_fee']; // Miscellaneous Fee
        $total_whole_year = $_POST['total_whole_year']; // Total Fee

        // Check if grade level already exists
        $checkSql = "SELECT * FROM payments WHERE grade_level = :grade_level";
        $checkQuery = $conn->prepare($checkSql);
        $checkQuery->bindParam(':grade_level', $grade_level, PDO::PARAM_STR);
        $checkQuery->execute();

        if ($checkQuery->rowCount() > 0) {
            $error = "This grade level already has a payment record.";
        } else {
            // Proceed with insertion
            $sql = "INSERT INTO payments (grade_level, registration_fee, tuition_fee, miscellaneous_fee, total_whole_year) 
            VALUES (:grade_level, :registration_fee, :tuition_fee, :miscellaneous_fee, :total_whole_year)";
            $query = $conn->prepare($sql);
            $query->bindParam(':grade_level', $grade_level, PDO::PARAM_STR);
            $query->bindParam(':registration_fee', $upon_enrollment, PDO::PARAM_STR);
            $query->bindParam(':tuition_fee', $tuition_fee, PDO::PARAM_STR);
            $query->bindParam(':miscellaneous_fee', $miscellaneous_fee, PDO::PARAM_STR);
            $query->bindParam(':total_whole_year', $total_whole_year, PDO::PARAM_STR);

            if ($query->execute()) {
                $msg = "Payment Added Successfully!";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Add Payment</title>

    <?php
    include 'link.php';
    ?>

    <style>
        .custom-bullet li {
            list-style-type: disc;
            margin-left: 20px;
        }
    </style>
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
                                <h4>Add Payment</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="accounting_dashboard.php">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="payment.php">Payment</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Add Payment
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <!-- Success and Error Messages -->
                    <?php if ($error) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlentities($error); ?>
                        </div>
                    <?php } else if ($msg) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlentities($msg); ?>
                        </div>
                    <?php } ?>

                    <form class="row g-3" method="post" name="add_payment" onSubmit="return valid();">
                        <!-- Grade Level and Upon Enrollment (Same Row) -->
                        <div class="col-md-6 mb-3">
                            <label for="grade_level" class="form-label">Grade Level</label>
                            <select class="custom-select col-12" id="grade_level" name="grade_level" required>
                                <option value="">Select Grade Level</option>
                                <?php
                                include "config1.php";
                                $sql = "SELECT * FROM gradelevel";
                                $result = mysqli_query($link, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['gradelevel_id'] . "'>" . $row['gradelevel_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="upon_enrollment" class="form-label">Registration Fee</label>
                            <input type="text" class="form-control" id="upon_enrollment">
                            <input type="hidden" name="upon_enrollment" id="upon_enrollment_hidden">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tuition_fee" class="form-label">Tuition Fee (10 Months)</label>
                            <input type="text" class="form-control" id="tuition_fee">
                            <input type="hidden" name="tuition_fee" id="tuition_fee_hidden">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="miscellaneous_fee" class="form-label">Miscellaneous Fee</label>
                            <input type="text" class="form-control" id="miscellaneous_fee">
                            <input type="hidden" name="miscellaneous_fee" id="miscellaneous_fee_hidden">
                        </div>

                        <!-- Total Tuition Fee  -->
                        <div class="col-6 mb-3">
                            <label for="total_whole_year" class="form-label">Total Fee</label>
                            <input type="text" class="form-control" id="total_whole_year">
                            <input type="hidden" name="total_whole_year" id="total_whole_year_hidden">
                        </div>

                        <!-- Additional Fees List -->
                        <div class="col-6 mb-3">
                            <ul class="custom-bullet ms-3">
                                <li>Miscellaneous</li>
                                <li>ID / Test Paper</li>
                                <li>Card</li>
                                <li>Developmental</li>
                                <li>Computer Basic</li>
                                <li>Laboratory / Library</li>
                            </ul>
                        </div>

                        <!-- Submit and Reset Buttons -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" name="add_payment">Submit</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'footer.php';
    ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Grade level fee data
    const gradeLevelFees = {
        'Grade 1': {
            registrationFee: 2000,
            tuitionFee: 8925,
            miscellaneousFee: 1628,
            totalFee: 25775
        },
        'Grade 2': {
            registrationFee: 2000,
            tuitionFee: 9450,
            miscellaneousFee: 1680,
            totalFee: 26300
        },
        'Grade 3': {
            registrationFee: 2000,
            tuitionFee: 9975,
            miscellaneousFee: 1733,
            totalFee: 26300
        },
        'Grade 4': {
            registrationFee: 2000,
            tuitionFee: 10290,
            miscellaneousFee: 1764,
            totalFee: 27140
        },
        'Grade 5': {
            registrationFee: 2000,
            tuitionFee: 10290,
            miscellaneousFee: 7350,
            totalFee: 27140
        },
        'Grade 6': {
            registrationFee: 2000,
            tuitionFee: 10290,
            miscellaneousFee: 7350,
            totalFee: 27140
        },
        'Grade 7': {
            registrationFee: 2000,
            tuitionFee: 1180,
            miscellaneousFee: 5000,
            totalFee: 27000
        },
        'Grade 8': {
            registrationFee: 2000,
            tuitionFee: 1180,
            miscellaneousFee: 5000,
            totalFee: 27000
        },
        'Grade 9': {
            registrationFee: 2000,
            tuitionFee: 1180,
            miscellaneousFee: 5000,
            totalFee: 27000
        },
        'Grade 10': {
            registrationFee: 2000,
            tuitionFee: 1180,
            miscellaneousFee: 5000,
            totalFee: 27000
        }
    };

    // Get form elements
    const gradeLevelSelect = document.getElementById('grade_level');
    const registrationFeeInput = document.getElementById('upon_enrollment');
    const registrationFeeHidden = document.getElementById('upon_enrollment_hidden');
    const tuitionFeeInput = document.getElementById('tuition_fee');
    const tuitionFeeHidden = document.getElementById('tuition_fee_hidden');
    const miscellaneousFeeInput = document.getElementById('miscellaneous_fee');
    const miscellaneousFeeHidden = document.getElementById('miscellaneous_fee_hidden');
    const totalFeeInput = document.getElementById('total_whole_year');
    const totalFeeHidden = document.getElementById('total_whole_year_hidden');

    // Function to update hidden fields when visible fields change
    function updateHiddenField(visibleField, hiddenField) {
        hiddenField.value = visibleField.value;
    }

    // Add event listeners to input fields for manual changes
    registrationFeeInput.addEventListener('input', function() {
        updateHiddenField(registrationFeeInput, registrationFeeHidden);
    });

    tuitionFeeInput.addEventListener('input', function() {
        updateHiddenField(tuitionFeeInput, tuitionFeeHidden);
    });

    miscellaneousFeeInput.addEventListener('input', function() {
        updateHiddenField(miscellaneousFeeInput, miscellaneousFeeHidden);
    });

    totalFeeInput.addEventListener('input', function() {
        updateHiddenField(totalFeeInput, totalFeeHidden);
    });

    // Function to populate form fields based on selected grade level
    gradeLevelSelect.addEventListener('change', function() {
        const selectedOption = gradeLevelSelect.options[gradeLevelSelect.selectedIndex];
        const selectedGradeName = selectedOption.text;
        
        if (gradeLevelFees[selectedGradeName]) {
            const fees = gradeLevelFees[selectedGradeName];
            
            // Update visible and hidden fields
            registrationFeeInput.value = fees.registrationFee;
            registrationFeeHidden.value = fees.registrationFee;
            
            tuitionFeeInput.value = fees.tuitionFee;
            tuitionFeeHidden.value = fees.tuitionFee;
            
            miscellaneousFeeInput.value = fees.miscellaneousFee;
            miscellaneousFeeHidden.value = fees.miscellaneousFee;
            
            totalFeeInput.value = fees.totalFee;
            totalFeeHidden.value = fees.totalFee;
        } else {
            // Clear fields if no matching grade level
            registrationFeeInput.value = '';
            registrationFeeHidden.value = '';
            tuitionFeeInput.value = '';
            tuitionFeeHidden.value = '';
            miscellaneousFeeInput.value = '';
            miscellaneousFeeHidden.value = '';
            totalFeeInput.value = '';
            totalFeeHidden.value = '';
        }
    });

    // Initialize hidden fields with visible field values on page load
    if (registrationFeeInput.value) updateHiddenField(registrationFeeInput, registrationFeeHidden);
    if (tuitionFeeInput.value) updateHiddenField(tuitionFeeInput, tuitionFeeHidden);
    if (miscellaneousFeeInput.value) updateHiddenField(miscellaneousFeeInput, miscellaneousFeeHidden);
    if (totalFeeInput.value) updateHiddenField(totalFeeInput, totalFeeHidden);
});
    </script>
</body>

</html>