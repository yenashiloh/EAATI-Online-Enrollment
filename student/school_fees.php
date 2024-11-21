<?php
include 'config.php';

session_start();
error_reporting(E_ALL);

if (!isset($_SESSION['student_id'])) {
    header('location:login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$error = "";
$msg = "";

// Retrieve student grade level
$grade_level_id = null;
$sql = "SELECT grade_level_id FROM student WHERE userId = :student_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':student_id', $student_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $grade_level_id = $row['grade_level_id'];
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
    <?php include 'asset.php'; ?>
</head>

<body>
    <?php include 'header.php';
    include 'sidebar.php'; ?>
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
            <div class="container mt-5">
                <h2 class="text-center mb-4 fw-bold">STATEMENT OF ACCOUNTS</h2>
                <h5 class="text-center mb-5">INSTALLMENT/MONTHLY BASIS</h5>

                <div class="row">
                    <?php if ($grade_level_id == 1): ?>
                        <!-- Grade 1 -->
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-8 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center">GRADE 1</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">8,925</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">18,275</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,628</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱25,775</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($grade_level_id == 2): ?>
                        <!-- Grade 2 -->
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-8 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center">GRADE 2</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">9,450</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">18,800</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,680</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱26,300</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($grade_level_id == 3): ?>
                        <!-- Grade 3 -->
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-8 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center">GRADE 3</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">9,975</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">19,325</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,733</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱26,825</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($grade_level_id == 4): ?>
                        <!-- Grade 4 -->
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-8 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center">GRADE 4</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">10,290</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">19,640</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,764</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱27,140</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($grade_level_id == 5): ?>
                        <!-- Grade 5 -->
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-8 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center">GRADE 5</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">10,290</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">19,640</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,764</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱27,140</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($grade_level_id == 6): ?>
                        <!-- Grade 6 -->
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-8 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center">GRADE 6</div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000</td>
                                                </tr>
                                                <tr>
                                                    <th>Tuition Fee (10 Months)</th>
                                                    <td class="text-end">10,290</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">7,350</td>
                                                </tr>
                                                <tr>
                                                    <th>Total Fee</th>
                                                    <td class="text-end">19,640</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Fee misc. & tuition fee (10 Months)</th>
                                                    <td class="text-end">1,764</td>
                                                </tr>
                                                <tr>
                                                    <th>BOOKS</th>
                                                    <td class="text-end">7,500</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱27,140</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($grade_level_id >= 7 && $grade_level_id <= 10): ?>
                        <!-- Grade 7-9 -->
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-8 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-header text-center"><?php echo "Grade " . $grade_level_id; ?></div>
                                    <div class="card-body">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Registration Fee</th>
                                                    <td class="text-end">2,000.00</td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Miscellaneous Fee
                                                        <ul style="list-style-type: disc; margin-left: 20px;">
                                                            <li>ID / Test Paper</li>
                                                            <li>Card</li>
                                                            <li>Developmental</li>
                                                            <li>Computer Basic</li>
                                                            <li>Laboratory / Library</li>
                                                        </ul>
                                                    </th>
                                                    <td class="text-end">5,000.00</td>
                                                </tr>

                                                <tr>
                                                    <th>School ID</th>
                                                    <td class="text-end">350.00</td>
                                                </tr>
                                                <tr>
                                                    <th>Books (1 Set)</th>
                                                    <td class="text-end">7,850.00</td>
                                                </tr>
                                                <tr>
                                                    <th>Monthly Tuitions (10 Months)</th>
                                                    <td class="text-end">1,180.00</td>
                                                </tr>
                                                <tr class="total-row">
                                                    <th>TOTAL FEE</th>
                                                    <td class="text-end">₱27,000.00</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-center text-danger"><strong>FREE: Book Bag Only</strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            <?php else: ?>
                <div class="col-md-12">
                    <div class="alert alert-warning text-center">
                        No fee structure is available for your grade level at the moment.
                    </div>
                </div>
            <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>