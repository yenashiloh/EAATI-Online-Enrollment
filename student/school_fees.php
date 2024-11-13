<?php

include 'config.php';

session_start();

$student_id = $_SESSION['student_id'];

if(!isset($student_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
}

?>

<?php

include 'config1.php';
// Check if there are any installment payments made by the user
$sql_installment = "SELECT COUNT(*) AS installment_count FROM transactions WHERE user_id = $student_id AND payment_type = 'installment'";
$result_installment = mysqli_query($link, $sql_installment);
$row_installment = mysqli_fetch_assoc($result_installment);
$installment_count = $row_installment['installment_count'];

// If there are installment payments, disable the installment option
if ($installment_count > 0) {
    echo '<script>document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("payment_type").getElementsByTagName("option")[1].disabled = true;
    });</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Parent</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include 'asset.php';?>

    

</head>

<body>

    <?php 
    include 'header.php';
    include 'sidebar.php';
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>School Fees</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">School Fees</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">

            <div class="row">
                <div class="col-lg-8">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"></h5>
                        </div>
                        <?php
                    // Include config file
                    require_once "config1.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT *
                    FROM transactions
                    WHERE user_id = $student_id;";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Reference No.</th>";
                                        echo "<th>Payment Method</th>";
                                        echo "<th>Payment Type</th>";
                                        echo "<th>Payment Amount</th>";
                                        echo "<th>Payment Date</th>";
                                        echo "<th>Status</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                       
                                        echo "<td>" .$row['reference_number']."</td>";
                                        echo "<td>" . $row['payment_method'] . "</td>";
                                        echo "<td>" . $row['payment_type'] . "</td>";
                                        echo "<td>" .'₱'.''. $row["payment_amount"] . "</td>";
                                        echo "<td>". $row["created_at"] . "</td>";
                                        echo "<td>";
                                            if ($row["status"] == 1) {
                                                echo '<span class="badge bg-success text-dark">Verified</span>';
                                            } else if( $row['status'] == 2) {
                                                echo '<span class="badge bg-danger text-dark">Rejected</span>';
                                            } else{
                                                echo '<span class="badge bg-warning text-dark">Not yet verified</span>';
                                            }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                 
                    ?>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div>
                            <table class="table table-hover">
                   
                    <tbody>
                    
                            <?php
// Include config file
require_once "config1.php";

// Get the total amount paid by the user
$sql = "SELECT SUM(payment_amount) AS total_paid FROM transactions WHERE user_id = $student_id and status = 1";
$totalPaidResult = mysqli_query($link, $sql);
$totalPaidRow = mysqli_fetch_assoc($totalPaidResult);
$totalPaid = $totalPaidRow['total_paid'];

// Attempt select query execution
$sql = "SELECT *
        FROM student
        INNER JOIN payments on student.grade_level = payments.grade_level 
        WHERE student.userId = $student_id and isVerified=1";
if($result = mysqli_query($link, $sql)){
    // Add your additional query here    
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_array($result)) {

                        echo'<tr>';
                        echo'<td class="col-md-9"><em>Total Tuition Fee:</em></h4></td>';
                        echo'<td>   </td>'; 
                        echo'<td>   </td>';
                        echo'<td class="col-md-1 text-center">₱'.$row['total_whole_year'].'</td>';
                        echo'</tr>';
                        
                        /* echo'<tr>';
                        echo'<td class="col-md-9"><em>Book Fee:</em></h4></td>';
                        echo'<td>   </td>'; 
                        echo'<td>   </td>';
                        echo'<td class="col-md-1 text-center">₱'.$row['books'].'</td>';
                        echo'</tr>';

                        echo'<tr>';
                        echo'<td class="col-md-9"><em>School Uniform Fee:</em></h4></td>';
                        echo'<td>   </td>'; 
                        echo'<td>   </td>';
                        echo'<td class="col-md-1 text-center">₱'.$row['school_uniform'].'</td>';
                        echo'</tr>';

                        echo'<tr>';
                        echo'<td class="col-md-9"><em>P.E Uniform:</em></h4></td>';
                        echo'<td>   </td>'; 
                        echo'<td>   </td>';
                        echo'<td class="col-md-1 text-center">₱'.$row['pe_uniform'].'</td>';
                        echo'</tr>'; */
                        
                        echo'<tr>';
                            echo'<td>   </td>';
                            echo'<td class="text-right"><h4><strong>Total</strong></h4></td>';
                            echo'<td class="text-right"><h4><strong>Balance: </strong></h4></td>';
                            $total = $row['total_whole_year'] + $row['books'] + $row['school_uniform'] + $row['pe_uniform'];
                            $remainingBalance = $total - $totalPaid;
                            
                            echo'<td class="text-center text-danger"><h4><strong>₱'.$remainingBalance.'</strong></h4></td>';
                        echo'</tr>';

                        echo '</tbody>';
                        echo '</table>';
                        if($remainingBalance > 0){
                        echo'<div style="text-align: center;">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">Make a Payment</button>
                        </div>';
                        
                        }
                        
        }
        // Free result set
        mysqli_free_result($result);
    } else{
        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
    }
}

// Close connection

?>                          
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Make a Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <!-- Payment form -->
                <!-- Payment form -->
            <!-- Payment form -->
            <?php
// Check if there are pending transactions
$sql_pending = "SELECT COUNT(*) AS pending_count FROM transactions WHERE user_id = $student_id AND (status = 0 OR status IS NULL)";
$result_pending = mysqli_query($link, $sql_pending);
$row_pending = mysqli_fetch_assoc($result_pending);
$pending_count = $row_pending['pending_count'];

// If there are pending transactions, prevent making new payments
if ($pending_count > 0) {
    echo '<div class="alert alert-danger">You have pending transactions. Please wait for verification before making new payments.</div>';
} else { ?>
<form id="paymentForm" method="post" action="make_payment.php" enctype="multipart/form-data">
<div class="mb-3">
    <label for="payment_type" class="form-label">Payment Type</label>
    <select class="form-select" id="payment_type" name="payment_type">
        <option value="cash">Cash</option>
        <option value="installment">Installment</option>
    </select>
</div>


<div id="installmentFields" style="display: none;">
            <div class="mb-3">
                <label for="installment_type" class="form-label">Installment Type</label>
                <select class="form-select" id="installment_type" name="installment_type">
                    <option value="upon_enrollment">Upon Enrollment</option>
                    <option value="partial">Partial</option>
                </select>
            </div>
        </div>

    <div class="mb-3">
        <label for="payment_amount" class="form-label">Payment Amount</label>
        <input type="text" class="form-control" id="payment_amount" name="payment_amount" required>
    </div>
    <div class="mb-3">
    <label class="form-label">Payment Method</label>
    <div class="form-check">
        <div style="display: inline-block; margin-right: 20px;">
            <label class="form-check-label" for="payment_method_cash">
                <img src="../images/cash_icon.png" alt="Cash Icon" class="payment-icon" style="width: 75px; height: 65px; vertical-align: middle; margin-right: 5px;">
            </label>
            <input class="form-check-input" type="radio" name="payment_method" id="payment_method_cash" value="Cash" checked>
        </div>
        <!-- <div style="display: inline-block;">
            <label class="form-check-label" for="payment_method_gcash">
                <img src="../images/gcash_icon.png" alt="GCash Icon" class="payment-icon" style="width: 200px; height: 65px; vertical-align: middle; margin-right: 5px;">
            </label>
            <input class="form-check-input" type="radio" name="payment_method" id="payment_method_gcash" value="GCash">
        </div> -->
    </div>
</div>

<div id="additionalFields" class="mb-3" style="display: none;">

    <div class="mb-3" style="text-align: center;">
        <img id="uploadedImage" src="../images/gcash.png" alt="gcash qr">
    </div>
    <!-- Additional fields for GCash payment method -->
    <div class="mb-3">
        <label for="gcash_number" class="form-label">GCash Number</label>
        <input type="text" class="form-control" id="gcash_number" name="gcash_number">
    </div>
    <div class="mb-3">
        <label for="reference_number" class="form-label">Reference Number</label>
        <input type="text" class="form-control" id="reference_number" name="reference_number">
    </div>
    <div class="mb-3">
        <label for="screenshot" class="form-label">Upload Screenshot</label>
        <input type="file" class="form-control" id="screenshot" name="screenshot">
    </div>
    <!-- Add more input fields as needed -->
</div>
    <!-- Add more fields as needed -->
    <button type="submit" class="btn btn-primary">Make Payment</button>
</form>
<?php
}
?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php

require_once "config1.php";

$sql_installment_count = "SELECT COUNT(*) AS installment_count FROM transactions WHERE user_id = $student_id AND payment_type = 'installment' AND status = 1";
$result_installment_count = mysqli_query($link, $sql_installment_count);
$row_installment_count = mysqli_fetch_assoc($result_installment_count);
$installment_count = $row_installment_count['installment_count'];

// Get the total amount paid by the user
$sql = "SELECT SUM(payment_amount) AS total_paid FROM transactions WHERE user_id = $student_id and status = 1";
$totalPaidResult = mysqli_query($link, $sql);
$totalPaidRow = mysqli_fetch_assoc($totalPaidResult);
$totalPaid = $totalPaidRow['total_paid'];

// Attempt select query execution
$sql = "SELECT *
        FROM student
        INNER JOIN payments on student.grade_level = payments.grade_level 
        WHERE student.userId = $student_id";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_array($result)) {
            $balance = $row['total_whole_year'] + $row['books'] + $row['school_uniform'] + $row['pe_uniform'];
            $remainingBalance = $balance - $totalPaid;
            $installment_balance = $row['upon_enrollment'];
            $partialPayment = $row['partial_upon'];

        }
        // Free result set
        mysqli_free_result($result);
    } else{
        $installment_balance = $row['upon_enrollment'];
    }
} else{
    echo "Oops! Something went wrong. Please try again later.";
}
// Close connection
mysqli_close($link);
?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var paymentTypeSelect = document.getElementById("payment_type");
    var paymentAmountInput = document.getElementById("payment_amount");
    var balance = <?php echo json_encode($remainingBalance); ?>; 
    var partialUpon = <?php echo json_encode($partialPayment); ?>; 
    var installment_balance = <?php echo json_encode($installment_balance); ?>; // Assigning the balance to JavaScript variable

    var cashRadio = document.getElementById("payment_method_cash");
    var gcashRadio = document.getElementById("payment_method_gcash");
    var additionalFieldsDiv = document.getElementById("additionalFields");
    var installmentFieldsDiv = document.getElementById("installmentFields");
    var installmentType = document.getElementById("installment_type");

    // Set initial state based on default selection
    if (cashRadio.checked) {
        additionalFieldsDiv.style.display = 'none';
    } else if (gcashRadio.checked) {
        additionalFieldsDiv.style.display = 'block';
    }

    // Add event listeners to radio buttons to toggle additional fields
    cashRadio.addEventListener("change", function() {
        additionalFieldsDiv.style.display = 'none';
    });

    gcashRadio.addEventListener("change", function() {
        additionalFieldsDiv.style.display = 'block';
    });

    // Function to update payment amount based on payment type
    function updatePaymentAmount() {
        var selectedPaymentType = paymentTypeSelect.value;
        var selectedInstallmentType = installmentType.value;
        if (selectedPaymentType === 'cash') {
            // Set payment amount to the current balance
            var installmentPaid = <?php echo json_encode($installment_count); ?>;
            if(installmentPaid > 0){
                // Divide the remaining balance by 10 if there are paid installments
                paymentAmountInput.value = balance / 10;
            } else {
                paymentAmountInput.value = balance;
            }
        } else if(selectedPaymentType === 'installment') {
            // Check if there are any paid transactions with payment type "installment"
            var installmentPaid = <?php echo json_encode($installment_count); ?>;
            if(installmentPaid > 0){
                // Divide the remaining balance by 10 if there are paid installments
                paymentAmountInput.value = balance / 10;
            } else {
                // Otherwise, set payment amount as usual
                if(selectedInstallmentType === 'upon_enrollment'){
                    paymentAmountInput.value = installment_balance;
                } else {
                    paymentAmountInput.value = partialUpon;
                }
            }
        } else {
            paymentAmountInput.value = '';
        }
    }

    // Set initial state based on default selection
    updatePaymentAmount();

    // Add event listener to payment type select
    paymentTypeSelect.addEventListener("change", updatePaymentAmount);
    installmentType.addEventListener("change", updatePaymentAmount);

    function updatePaymentFields() {
        if (paymentTypeSelect.value === 'installment') {
            installmentFieldsDiv.style.display = 'block';
        } else {
            installmentFieldsDiv.style.display = 'none';
        }
    }

    // Set initial state
    updatePaymentFields();

    // Add event listener to payment type select
    paymentTypeSelect.addEventListener("change", updatePaymentFields);

});
</script>





    <?php

    include 'script.php';
  ?>

</body>

</html>
