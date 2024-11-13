<?php

include 'config.php';

session_start();
error_reporting(0);
$accounting_id = $_SESSION['accounting_id'];

if(!isset($accounting_id)){
   header('location:login.php');
}
else{
    if(isset($_POST['edit_payment'])) {
        $id = $_POST['payment_id']; // Get payment ID from the form
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
            $msg = "Payment Updated Successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <?php include 'asset.php';?>

</head>

<body>

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

<main id="main" class="main">

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Payment</h5>

        <form class="row g-3"  method="post" name="edit_payment" onSubmit="return valid();">
    <!-- Alert Messages -->
    <?php if(isset($error)){?>
        <div class="alert alert-danger" role="alert">
            <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
        </div>
    <?php } else if(isset($msg)){?>
        <div class="alert alert-success" role="alert">
            <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
        </div>
    <?php }?>
    
    <!-- Hidden Payment ID Field -->
    <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
    
    <!-- Grade Level Field -->
    <div class="col-md-6">
    <label for="grade_level" class="form-label">Grade Level</label>
    <select class="form-select" id="grade_level" name="grade_level" required>
        <option value="">Select Grade Level</option>
        <?php
        include "config1.php";
        // Fetch grade levels from database
        $sql = "SELECT * FROM gradelevel";
        $result = mysqli_query($link, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the grade level matches the one stored in the database
            $selected = ($row['gradelevel_id'] == $payment['grade_level']) ? 'selected' : '';
            echo "<option value='" . $row['gradelevel_id'] . "' $selected>" . $row['gradelevel_name'] . "</option>";
        }
        ?>
    </select>
</div>
    
    <!-- Tuition June to March Field -->
    <!-- <div class="col-md-6">
        <label for="tuition_june_to_march" class="form-label">Tuition August to May</label>
        <input type="text" id="tuition_june_to_march" class="form-control" placeholder="Tuition June to March" name="tuition_june_to_march" value="<?php echo $payment['tuition_june_to_march']; ?>">
    </div>
     -->
    <!-- Partial Upon Field -->
    <div class="col-md-6">
        <label for="partial_upon" class="form-label">Partial Upon</label>
        <input type="text" id="partial_upon" class="form-control" placeholder="Partial Upon" name="partial_upon" value="<?php echo $payment['partial_upon']; ?>">
    </div>
    
    <!-- Total Whole Year Field -->
    <div class="col-md-6">
        <label for="total_whole_year" class="form-label">Total Tuition Fee</label>
        <input type="text" id="total_whole_year" class="form-control" placeholder="Total Whole Year" name="total_whole_year" value="<?php echo $payment['total_whole_year']; ?>">
    </div>
    
    <!-- PE Uniform Field -->
    <!-- <div class="col-md-6">
        <label for="pe_uniform" class="form-label">PE Uniform</label>
        <input type="text" id="pe_uniform" class="form-control" placeholder="PE Uniform" name="pe_uniform" value="<?php echo $payment['pe_uniform']; ?>">
    </div> -->
    
    <!-- Books Field -->
    <!-- <div class="col-md-6">
        <label for="books" class="form-label">Books</label>
        <input type="text" id="books" class="form-control" placeholder="Books" name="books" value="<?php echo $payment['books']; ?>">
    </div> -->
    
    <!-- School Uniform Field -->
    <!-- <div class="col-md-6">
        <label for="school_uniform" class="form-label">School Uniform</label>
        <input type="text" id="school_uniform" class="form-control" placeholder="School Uniform" name="school_uniform" value="<?php echo $payment['school_uniform']; ?>">
    </div> -->
    
    <!-- Upon Enrollment Field -->
    <div class="col-md-6">
        <label for="upon_enrollment" class="form-label">Upon Enrollment</label>
        <input type="text" id="upon_enrollment" class="form-control" placeholder="Upon Enrollment" name="upon_enrollment" value="<?php echo $payment['upon_enrollment']; ?>">
    </div>
    
    <!-- Submit Button -->
    <div class="text-center">
        <button type="submit" class="btn btn-primary" name="edit_payment">Update</button>
        <a href="payment.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>

    </div>
</div>
</main><!-- End #main -->


<?php
    include 'footer.php';
    include 'script.php';
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
        var uponEnrollment = Math.round(total * 0.5345); // Calculate 46.5% of total
        document.getElementById('upon_enrollment').value = uponEnrollment;
    }
});
</script>


</body>

</html>
<?php } ?>
