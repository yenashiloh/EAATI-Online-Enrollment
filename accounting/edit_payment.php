<?php

include 'config.php';

session_start();
error_reporting(0);
$accounting_id = $_SESSION['accounting_id'];

if(!isset($accounting_id)){
   header('location:../login.php');
}
else{
    if(isset($_POST['edit_payment'])) {
        $id = $_POST['payment_id']; 
        $grade_level = $_POST['grade_level'];
        $tuition_june_to_march = $_POST['tuition_june_to_march'];
        $partial_upon = $_POST['partial_upon'];
        $total_whole_year = $_POST['total_whole_year'];
        $pe_uniform = $_POST['pe_uniform'];
        $books = $_POST['books'];
        $school_uniform = $_POST['school_uniform'];
        $upon_enrollment = $_POST['upon_enrollment'];

        $sql = "UPDATE payments 
                SET grade_level = :grade_level, tuition_june_to_march = :tuition_june_to_march, 
                    partial_upon = :partial_upon, total_whole_year = :total_whole_year, 
                    pe_uniform = :pe_uniform, books = :books, school_uniform = :school_uniform,
                    upon_enrollment = :upon_enrollment
                WHERE payment_id = :id";

        $query = $conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->bindParam(':grade_level', $grade_level, PDO::PARAM_STR);
        $query->bindParam(':tuition_june_to_march', $tuition_june_to_march, PDO::PARAM_STR);
        $query->bindParam(':partial_upon', $partial_upon, PDO::PARAM_STR);
        $query->bindParam(':total_whole_year', $total_whole_year, PDO::PARAM_STR);
        $query->bindParam(':pe_uniform', $pe_uniform, PDO::PARAM_STR);
        $query->bindParam(':books', $books, PDO::PARAM_STR);
        $query->bindParam(':school_uniform', $school_uniform, PDO::PARAM_STR);
        $query->bindParam(':upon_enrollment', $upon_enrollment, PDO::PARAM_STR);
        
        if($query->execute()) {
            $msg = "Payment Updated Successfully!";
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
		<title>School Fees</title>

		<?php
			include 'link.php';
		?>
		<!-- End Google Tag Manager -->
	</head>
	<body class="sidebar-light">
    <?php 
        include 'header.php';
        include 'sidebar.php';

        // Fetch payment details for editing
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM payments WHERE payment_id = :id";
            $query = $conn->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $payment = $query->fetch(PDO::FETCH_ASSOC);
        }
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
											<a href="accounting_dashboard.php">Dashboard</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="payment.php">Payment</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Edit Payment
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <h5 class="card-title">Edit Payment</h5>
                    <!-- Alert Messages -->
                    <?php if(isset($error)){?>
                    <div class="alert alert-danger mb-4" role="alert">
                        <?php echo htmlentities($error); ?>
                    </div>
                    <?php } else if(isset($msg)){?>
                    <div class="alert alert-success mb-4" role="alert">
                        <?php echo htmlentities($msg); ?>
                    </div>
                    <?php }?>

                    <form class="row g-3" method="post" name="edit_payment" onSubmit="return valid();">
                        <!-- Hidden Payment ID Field -->
                        <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
                        
                        <!-- Grade Level Field -->
                        <div class="col-md-6 mb-3">
                            <label for="grade_level" class="form-label">Grade Level</label>
                            <select class="custom-select col-12" id="grade_level" name="grade_level" required>
                                <option value="">Select Grade Level</option>
                                <?php
                                include "config1.php";
                                $sql = "SELECT * FROM gradelevel";
                                $result = mysqli_query($link, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $selected = ($row['gradelevel_id'] == $payment['grade_level']) ? 'selected' : '';
                                    echo "<option value='" . $row['gradelevel_id'] . "' $selected>" . $row['gradelevel_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Partial Upon Field -->
                        <div class="col-md-6 mb-3">
                            <label for="partial_upon" class="form-label">Partial Upon</label>
                            <input type="text" id="partial_upon" class="form-control" placeholder="Partial Upon" name="partial_upon" value="<?php echo $payment['partial_upon']; ?>">
                        </div>

                        <!-- Total Whole Year Field -->
                        <div class="col-md-6 mb-3">
                            <label for="total_whole_year" class="form-label">Total Tuition Fee</label>
                            <input type="text" id="total_whole_year" class="form-control" placeholder="Total Whole Year" name="total_whole_year" value="<?php echo $payment['total_whole_year']; ?>">
                        </div>

                        <!-- Upon Enrollment Field -->
                        <div class="col-md-6 mb-3">
                            <label for="upon_enrollment" class="form-label">Upon Enrollment</label>
                            <input type="text" id="upon_enrollment" class="form-control" placeholder="Upon Enrollment" name="upon_enrollment" value="<?php echo $payment['upon_enrollment']; ?>">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary" name="edit_payment">Update</button>
                            <a href="payment.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
			</div>
		</div>

	<?php
		include 'footer.php';
    ?>
		
    <script>
    document.getElementById('total_whole_year').addEventListener('input', function() {
        var total = parseFloat(document.getElementById('total_whole_year').value);
        if (!isNaN(total)) {
            var partialUpon = Math.round(total * 0.3003); 
            document.getElementById('partial_upon').value = partialUpon;
        }
    });
    </script>

    <script>
    document.getElementById('total_whole_year').addEventListener('input', function() {
        var total = parseFloat(document.getElementById('total_whole_year').value);
        if (!isNaN(total)) {
            var uponEnrollment = Math.round(total * 0.5345); 
            document.getElementById('upon_enrollment').value = uponEnrollment;
        }
    });
    </script>

	</body>
</html>
<?php } ?>