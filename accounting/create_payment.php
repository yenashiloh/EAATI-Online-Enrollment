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
        $upon_enrollment = $_POST['upon_enrollment'];
        $tuition_june_to_march = $_POST['tuition_june_to_march'];
        $partial_upon = $_POST['partial_upon'];
        $total_whole_year = $_POST['total_whole_year'];
        $pe_uniform = $_POST['pe_uniform'];
        $books = $_POST['books'];
        $school_uniform = $_POST['school_uniform'];
        $miscellaneous_fee = $_POST['miscellaneous_fee'];

        // Divided values
        $upon_enrollment_divided = $_POST['upon_enrollment_divided'];
        $partial_upon_divided = $_POST['partial_upon_divided'];

        // Check if grade level already exists
        $checkSql = "SELECT * FROM payments WHERE grade_level = :grade_level";
        $checkQuery = $conn->prepare($checkSql);
        $checkQuery->bindParam(':grade_level', $grade_level, PDO::PARAM_STR);
        $checkQuery->execute();

        if ($checkQuery->rowCount() > 0) {
            $error = "This grade level already has a payment record.";
        } else {
            // Proceed with insertion
            $sql = "INSERT INTO payments (grade_level, upon_enrollment, tuition_june_to_march, partial_upon, total_whole_year, pe_uniform, books, school_uniform, miscellaneous_fee,
            upon_enrollment_divided, partial_upon_divided) 
            VALUES (:grade_level, :upon_enrollment, :tuition_june_to_march, :partial_upon, :total_whole_year, :pe_uniform, :books, :school_uniform, :miscellaneous_fee,
            :upon_enrollment_divided, :partial_upon_divided)";
            $query = $conn->prepare($sql);
            $query->bindParam(':grade_level', $grade_level, PDO::PARAM_STR);
            $query->bindParam(':upon_enrollment', $upon_enrollment, PDO::PARAM_STR);
            $query->bindParam(':tuition_june_to_march', $tuition_june_to_march, PDO::PARAM_STR);
            $query->bindParam(':partial_upon', $partial_upon, PDO::PARAM_STR);
            $query->bindParam(':total_whole_year', $total_whole_year, PDO::PARAM_STR);
            $query->bindParam(':pe_uniform', $pe_uniform, PDO::PARAM_STR);
            $query->bindParam(':books', $books, PDO::PARAM_STR);
            $query->bindParam(':school_uniform', $school_uniform, PDO::PARAM_STR);
            $query->bindParam(':miscellaneous_fee', $miscellaneous_fee, PDO::PARAM_STR);
            $query->bindParam(':upon_enrollment_divided', $upon_enrollment_divided, PDO::PARAM_STR);
            $query->bindParam(':partial_upon_divided', $partial_upon_divided, PDO::PARAM_STR);

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
                            <label for="upon_enrollment" class="form-label">Upon Enrollment</label>
                            <input type="text" class="form-control" id="upon_enrollment" readonly>
                            <input type="hidden" name="upon_enrollment" id="upon_enrollment_hidden">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="miscellaneous_fee" class="form-label">Miscellaneous Fee</label>
                            <input type="text" class="form-control" id="miscellaneous_fee" readonly>
                            <input type="hidden" name="miscellaneous_fee" id="miscellaneous_fee_hidden">
                        </div>

                        <!-- Total Tuition Fee  -->
                        <div class="col-6 mb-3">
                            <label for="total_whole_year" class="form-label">Total Tuition Fee</label>
                            <input type="text" class="form-control" id="total_whole_year" readonly>
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
            // Get the grade level select element
            const gradeLevelSelect = document.getElementById('grade_level');

            // Define the tuition fees for each grade level name instead of ID
            const tuitionFees = {
                'Grade 1': 8925,
                'Grade 2': 9450,
                'Grade 3': 9975,
                'Grade 4': 10290,
                'Grade 5': 10290,
                'Grade 6': 10290,
                'Grade 7': 10620,
                'Grade 8': 10620,
                'Grade 9': 10620,
                'Grade 10': 10620
            };

            // Function to format number with commas
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Add event listener to the grade level select
            gradeLevelSelect.addEventListener('change', function() {
                // Get the selected option's text (grade level name) instead of value (ID)
                const selectedGradeName = this.options[this.selectedIndex].text;

                // Get the input fields
                const uponEnrollmentField = document.getElementById('upon_enrollment');
                const uponEnrollmentHidden = document.getElementById('upon_enrollment_hidden');
                const miscellaneousField = document.getElementById('miscellaneous_fee');
                const miscellaneousHidden = document.getElementById('miscellaneous_fee_hidden');
                const totalTuitionField = document.getElementById('total_whole_year');
                const totalTuitionHidden = document.getElementById('total_whole_year_hidden');

                // If a valid grade level is selected
                if (selectedGradeName && tuitionFees[selectedGradeName]) {
                    // Set fixed "Upon Enrollment" fee (2000)
                    const enrollmentFee = 2000;

                    // Get tuition fee for the selected grade
                    const tuitionFee = tuitionFees[selectedGradeName];

                    // Calculate miscellaneous fee (10% of tuition fee)
                    const miscFee = Math.floor(tuitionFee * 0.1);

                    // Set the formatted values for display
                    uponEnrollmentField.value = formatNumber(enrollmentFee);
                    miscellaneousField.value = formatNumber(miscFee);
                    totalTuitionField.value = formatNumber(tuitionFee);

                    // Set the hidden values for form submission
                    uponEnrollmentHidden.value = enrollmentFee;
                    miscellaneousHidden.value = miscFee;
                    totalTuitionHidden.value = tuitionFee;
                } else {
                    // Clear fields if no valid grade level is selected
                    uponEnrollmentField.value = '';
                    miscellaneousField.value = '';
                    totalTuitionField.value = '';
                    uponEnrollmentHidden.value = '';
                    miscellaneousHidden.value = '';
                    totalTuitionHidden.value = '';
                }
            });

            // Form validation function
            window.valid = function() {
                // You can add validation logic here
                return true;
            };
        });
    </script>
</body>

</html>