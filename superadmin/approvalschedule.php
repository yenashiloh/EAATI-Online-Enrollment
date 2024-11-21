<?php
include 'config.php'; 

session_start();

$superadmin_id = $_SESSION['superadmin_id'];

if (!isset($superadmin_id)) {
    header('location:login.php');
    exit; 
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $enrollmentschedule_id = $_GET['id'];

    if ($action == 'approve') {
        $status = 'Approved';
    } elseif ($action == 'decline') {
        $status = 'Declined';
    }

    if (isset($status)) {
        $sql = "UPDATE enrollmentschedule SET status = :status WHERE enrollmentschedule_id = :enrollmentschedule_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':enrollmentschedule_id', $enrollmentschedule_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $message = "Schedule status updated to $status";
            $alert_class = "alert-success"; 
        } else {
            $message = "Error updating status: " . $stmt->errorInfo()[2];
            $alert_class = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Superadmin</title>
    <?php include 'asset.php'; ?>
</head>

<body>

    <?php
    include 'header.php';
    include 'sidebar.php';
    ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Approval Schedule Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Approval Schedule</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"></h5>

                            <!-- Display Success/Error Message -->
                            <?php if (isset($message)): ?>
                                <div class="alert <?php echo $alert_class; ?>" role="alert">
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php
                        $sql = "SELECT enrollmentschedule.*, gradelevel.gradelevel_name 
                                FROM enrollmentschedule 
                                INNER JOIN gradelevel ON enrollmentschedule.gradelevel_id = gradelevel.gradelevel_id";

                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        if ($stmt->rowCount() > 0) {
                            echo '<table class="table datatable">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Grade Level</th>";
                            echo "<th>Start Date</th>";
                            echo "<th>End Date</th>";
                            echo "<th>Status</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . $row['enrollmentschedule_id'] . "</td>";
                                echo "<td>" . $row['gradelevel_name'] . "</td>";
                                echo "<td>" . $row['start_date'] . "</td>";
                                echo "<td>" . $row['end_date'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                echo "<td>";

                                if ($row['status'] == 'For Review') {
                                    echo "<a href='approvalschedule.php?action=approve&id=" . $row['enrollmentschedule_id'] . "' class='btn btn-success btn-sm'>Approve</a> ";
                                    echo "<a href='approvalschedule.php?action=decline&id=" . $row['enrollmentschedule_id'] . "' class='btn btn-danger btn-sm'>Decline</a>";
                                } elseif ($row['status'] == 'Approved') {
                                    echo "<a href='approvalschedule.php?action=decline&id=" . $row['enrollmentschedule_id'] . "' class='btn btn-danger btn-sm'>Decline</a>";
                                } elseif ($row['status'] == 'Declined') {
                                    echo "<a href='approvalschedule.php?action=approve&id=" . $row['enrollmentschedule_id'] . "' class='btn btn-success btn-sm'>Approve</a>";
                                }


                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "No records found.";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <?php
    include 'footer.php';
    include 'script.php';
    ?>

</body>

</html>