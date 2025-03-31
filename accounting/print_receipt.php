<?php
include 'config.php';

session_start();

$accounting_id = $_SESSION['accounting_id'];

if(!isset($accounting_id)){
   header('location:../login.php');
}

// Get transaction ID from URL parameter
if(isset($_GET['id'])) {
    $transaction_id = $_GET['id'];
} else {
    exit("Invalid request. Please provide a valid transaction ID.");
}

try {
    // Updated the query to use transaction_id instead of id
    $sql = "SELECT transactions.*, student.name, student.isPaidUpon, student.grade_level_id, gradelevel.gradelevel_name
    FROM transactions
    LEFT JOIN student ON transactions.user_id = student.userId
    LEFT JOIN gradelevel ON student.grade_level_id = gradelevel.gradelevel_id
    WHERE transactions.transaction_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$transaction_id]);
    
    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // We have the transaction data in $row now
    } else {
        // URL doesn't contain valid id parameter
        exit("No valid record found.");
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .logo {
            max-width: 80px;
            float: left;
        }
        .school-info {
            text-align: center;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .details {
            margin-bottom: 20px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details table td {
            padding: 5px;
        }
        .payment-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .payment-details th, .payment-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .payment-details th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <img src="../asset/img/logo.png" alt="School Logo" class="logo">
            <div class="school-info">
                <h5 class="fw-bold">EASTERN ACHIEVER ACADEMY OF TAGUIG INC. (EAATI)</h5>
                <p>Blk 2 Lot 23 Lot 23 Phase 1 Pinagsama Village Taguig City</p>
                <p>Tel # 971-8424</p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="receipt-title">OFFICIAL RECEIPT</div>

        <div class="details">
            <table>
                <tr>
                    <td><strong>Reference No.:</strong></td>
                    <td><?php echo $row['reference_number']; ?></td>
                    <td><strong>Date:</strong></td>
                    <td><?php echo date('F d, Y', strtotime($row['created_at'] ?? $row['payment_date'] ?? date('Y-m-d'))); ?></td>
                </tr>
                <tr>
                    <td><strong>Student Name:</strong></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><strong>Grade Level:</strong></td>
                    <td><?php echo $row['gradelevel_name']; ?></td>
                </tr>
            </table>
        </div>

        <div class="payment-info">
            <h5 class="fw-bold mt-3">Payment Details</h3>
            <table class="payment-details">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Based on your database screenshot, using payment_amount instead of specific fee fields
                    ?>
                    <tr>
                        <td>Payment</td>
                        <td>₱ <?php echo number_format($row['payment_amount'], 2); ?></td>
                    </tr>
                    <?php if(isset($row['balance'])): ?>
                        <tr>
                            <td>Remaining Balance</td>
                            <td>₱ <?php echo number_format($row['balance'], 2); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Total Amount Paid</strong></td>
                        <td><strong>₱ <?php echo number_format($row['payment_amount'], 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="payment-method">
            <h5 class="fw-bold mt-4 mb-4">Payment Method</h5>
            <p><strong>Payment Type:</strong> <?php echo $row['payment_method']; ?></p>
            <?php if(!empty($row['gcash_number'])): ?>
            <p><strong>GCash Number:</strong> <?php echo $row['gcash_number']; ?></p>
            <?php endif; ?>
            <?php if(!empty($row['payment_type'])): ?>
            <p><strong>Payment Type:</strong> <?php echo $row['payment_type']; ?></p>
            <?php endif; ?>
            <?php if(!empty($row['installment_type'])): ?>
            <p><strong>Installment Type:</strong> <?php echo $row['installment_type']; ?></p>
            <?php endif; ?>
        </div>
        <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Print Receipt
        </button>
        <button class="btn btn-secondary" onclick="window.location.href='transact.php'">
            <i class="fas fa-arrow-left"></i> Back to Transactions
        </button>

        </div>
    </div>
    <!-- Bootstrap JS (for components like modals, dropdowns, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>