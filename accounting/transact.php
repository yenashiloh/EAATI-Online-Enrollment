<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Accounting</title>
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
            <h1>Transactions</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Transactions</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"></h5>

                            <?php
                            
                            // Check if the 'deleted' parameter is set and equals to 1
                            if(isset($_GET['reject']) && $_GET['reject'] == 1){
                                echo "<div class='alert alert-success'>Payment rejected successfully.</div>";
                            }

                            if(isset($_GET['verified']) && $_GET['verified'] == 1){
                                echo "<div class='alert alert-success'>Payment verified successfully.</div>";
                            }

                            if(isset($_GET['success']) && $_GET['success'] == 1){
                                echo "<div class='alert alert-success'>Transaction added successfully.</div>";
                            }
                            ?>
                            <!-- Add Transaction Button -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                                Add Transaction
                            </button>
                        </div>
                        

                        <?php
                        // Include config file
                        require_once "config1.php";
                        
                        // Attempt select query execution
                        $sql = "SELECT transactions.*, student.name, student.isPaidUpon FROM transactions
                        INNER JOIN student ON transactions.user_id = student.student_id";
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<table class="table datatable">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Student Name</th>";
                                            echo "<th>Reference No.</th>";
                                            echo "<th>Payment Method</th>";
                                            echo "<th>Payment Amount</th>";
                                            echo "<th>Payment Date</th>";
                                            echo "<th>Status</th>";
                                            echo "<th>Action</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                            echo "<td>" .$row['name']."</td>";  
                                            echo "<td>" .$row['reference_number']."</td>";
                                            echo "<td>" . $row['payment_method'];
                                            if ($row["payment_method"] == 'GCash') {
                                                // Display attachment view
                                                echo '<a href="'.$row['screenshot_path'].'" target="_blank"> - View Screenshot</a>';
                                            } else {
                                                echo '';
                                            }
                                            echo "</td>";
                                            echo "<td>" .'₱'.''. $row["payment_amount"] . "</td>";
                                            echo "<td>". $row["created_at"] . "</td>";
                                            echo "<td>";
                                                if ($row["status"] == 0) {
                                                    echo '<span class="badge bg-warning text-dark">Not yet verified</span>';
                                                } else if($row["status"] == 1){
                                                    echo '<span class="badge bg-success text-dark">Verified</span>';
                                                }
                                                else if($row["status"] == 2){
                                                    echo '<span class="badge bg-warning text-dark">Paid</span>';
                                                }else{
                                                    echo '<span class="badge bg-danger text-dark">Rejected</span>';
                                                }
                                            echo "</td>";
                                            echo "<td>";
                                                if ($row['status'] == 0 || $row['status'] === null) {
                                                echo '<a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#verifyModal'.$row['user_id'].'" title="Verify Record" data-toggle="tooltip"><span class="bi bi-check-circle-fill"></span></a>';
                                                
                                                // Verification Modal
                                                echo '
                                                <div class="modal fade" id="verifyModal'.$row['user_id'].'" tabindex="-1" aria-labelledby="verifyModalLabel'.$row['user_id'].'" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="verifyModalLabel'.$row['user_id'].'">Confirm Verification</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to verify this record?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <a href="verify.php?id='.$row['user_id'].'" class="btn btn-success">Verify</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';

                                                // Add the conditional display of the radio button based on `isPaidUpon`
                                                if ($row['isPaidUpon'] == 0 || $row['isPaidUpon'] === null) {
                                                    echo '<div class="form-check">
                                                            <input class="form-check-input" type="radio" name="installmentOption" id="installmentOption' . $row['user_id'] . '" value="installment">
                                                            <label class="form-check-label" for="installmentOption' . $row['user_id'] . '">
                                                                Installment
                                                            </label>
                                                          </div>';
                                                }

                                                echo '<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row['transaction_id'] . '" title="Delete Record"><span class="bi bi-x-circle-fill"></span></button>';
                                                // Delete Modal
                                                echo '
                                                <div class="modal fade" id="deleteModal'.$row['transaction_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['transaction_id'].'" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel'.$row['transaction_id'].'">Confirm Reject</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to reject this payment?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <a href="reject.php?id='.$row['transaction_id'].'" class="btn btn-danger">Reject</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';
                                                }
                                                echo '<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewModal' . $row['transaction_id'] . '" title="View Record"><span class="bi bi-eye-fill"></span></button>';
                                                // View Modal
                                                echo '
                                                <div class="modal fade" id="viewModal' . $row['transaction_id'] . '" tabindex="-1" aria-labelledby="viewModalLabel' . $row['transaction_id'] . '" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="viewModalLabel' . $row['transaction_id'] . '">View Record</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Content to display in the modal body -->
                                                                <p>Reference No.: ' . $row['reference_number'] . '</p>
                                                                <p>Payment Method: ' . $row['payment_method'] . '</p>
                                                                <p>Payment Amount: ₱' . $row["payment_amount"] . '</p>
                                                                <p>Payment Date: ' . $row["created_at"] . '</p>
                                                                <!-- You can add more details here as needed -->
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>';

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
     
                        // Close connection
                        mysqli_close($link);
                        ?>

                    </div>
                </div>

            </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Add Transaction Modal -->
    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTransactionModalLabel">Add Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_transaction.php" method="post">
                    <div class="mb-3">
                        <label for="studentName" class="form-label">Student Name</label>
                        <select class="form-select" id="studentName" name="student_id" required>
                            <?php
                            include "config1.php";
                            $sql = "SELECT * FROM student";
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_array($result)){
                                        echo '<option value="'. $row['student_id'] .'" data-is-paid-upon="'. $row['isPaidUpon'] .'">'. $row['name'] .'</option>';
                                    }
                                    mysqli_free_result($result);
                                } else{
                                    echo '<option value="">No students found</option>';
                                }
                            } else{
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
                        <label for="paymentMethod" class="form-label">Payment Method</label>
                        <select class="form-select" id="paymentMethod" name="payment_method" disabled>
                            <option value="Cash">Cash</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Payment Amount</label>
                        <input type="number" class="form-control" id="paymentAmount" name="payment_amount" value="0.0" required>
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
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    
    
    <?php
    include 'footer.php';
    include 'script.php';
    ?>

</body>

</html>
