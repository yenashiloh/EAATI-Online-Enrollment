<?php
include 'config.php';

session_start();
error_reporting(0);
$accounting_id = $_SESSION['accounting_id'];

if(!isset($accounting_id)) {
    header('location:../login.php');
} else {
    if(isset($_POST['add_payment'])) {
        // Database connection assumed to be established in config.php
        $grade_level = $_POST['grade_level'];
        $upon_enrollment = $_POST['upon_enrollment'];
        $tuition_june_to_march = $_POST['tuition_june_to_march'];
        $partial_upon = $_POST['partial_upon'];
        $total_whole_year = $_POST['total_whole_year'];
        $pe_uniform = $_POST['pe_uniform'];
        $books = $_POST['books'];
        $school_uniform = $_POST['school_uniform'];

        // Divided values
        $upon_enrollment_divided = $_POST['upon_enrollment_divided'];
        $partial_upon_divided = $_POST['partial_upon_divided'];
        
        $sql = "INSERT INTO payments (grade_level, upon_enrollment, tuition_june_to_march, partial_upon, total_whole_year, pe_uniform, books, school_uniform,
                                  upon_enrollment_divided, partial_upon_divided) 
            VALUES (:grade_level, :upon_enrollment, :tuition_june_to_march, :partial_upon, :total_whole_year, :pe_uniform, :books, :school_uniform,
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
            $query->bindParam(':upon_enrollment_divided', $upon_enrollment_divided, PDO::PARAM_STR);
            $query->bindParam(':partial_upon_divided', $partial_upon_divided, PDO::PARAM_STR);
        
        if($query->execute()) {
            $msg = "Payment Added Successfully!";
        } else {
            $error = "Something went wrong. Please try again";
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
                            <input type="number" class="form-control" id="upon_enrollment" name="upon_enrollment">
                        </div>

                        <!-- Total Tuition Fee  -->
                        <div class="col-6 mb-3">
                            <label for="total_whole_year" class="form-label">Total Tuition Fee</label>
                            <input type="number" class="form-control" id="total_whole_year" name="total_whole_year">
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

		<?php
		    include 'footer.php';
        ?>
		<?php } ?>
	</body>
</html>
