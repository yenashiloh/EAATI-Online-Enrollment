<?php
// Your database connection logic
include 'config.php';

// Start session and error reporting
session_start();
error_reporting(E_ALL);

// Check if the user is logged in
$teacher_id = $_SESSION['teacher_id'];
if (!isset($teacher_id)) {
    header('location:login.php');
    exit; // Stop further execution
}
else {
    $error = ""; // Initialize $error variable
    $msg = ""; // Initialize $error variable
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Registrar</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include 'asset.php';?>

</head>

<body>

    <?php 
    include 'header.php';
    include 'sidebar.php';
    // Fetch user details for editing
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM schedules WHERE id = :id";
        $query = $conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $student = $query->fetch(PDO::FETCH_ASSOC);
    }
?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Class</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="index.html">Schedule</a></li>
                    <li class="breadcrumb-item active">Class</li>
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
                        require_once "config1.php";

                        // Check if the schedule id is provided in the URL
                        if(isset($_GET['id'])) {
                            // Get the id from the URL
                            $schedule_id = $_GET['id'];
                            
                            // Attempt select query execution
                            $sql = "SELECT * FROM encodedgrades 
                                    INNER JOIN student ON encodedgrades.student_id = student.student_id 
                                    WHERE student.isVerified = 2 AND encodedgrades.schedule_id = $schedule_id";
                            
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) > 0){
                                    // Output data of each row
                                    echo '<table class="table datatable">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>Name</th>";
                                    echo "<th>1st Quarter</th>";
                                    echo "<th>2nd Quarter</th>";
                                    echo "<th>3rd Quarter</th>";
                                    echo "<th>4th Quarter</th>";
                                    echo "<th>Final</th>";
                                    echo "<th>Remarks</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";

                                        // Convert grades to integers
                                        $quarter1 = intval($row['quarter1']);
                                        $quarter2 = intval($row['quarter2']);
                                        $quarter3 = intval($row['quarter3']);
                                        $quarter4 = intval($row['quarter4']);

                                        // Compute final grade (average of quarters)
                                        $finalGrade = ($quarter1 + $quarter2 + $quarter3 + $quarter4) / 4;
                                        echo "<td>" . $quarter1 . "</td>";
                                        echo "<td>" . $quarter2 . "</td>";
                                        echo "<td>" . $quarter3 . "</td>";
                                        echo "<td>" . $quarter4 . "</td>";
                                        echo "<td>" . $finalGrade . "</td>";

                                        // Determine remarks
                                        $remarks = ($finalGrade < 74) ? "Failed" : "Passed";

                                        echo "<td>" . $remarks . "</td>";

                                        echo "<td>";
                                        // Button to open the modal and pass student id
                                        echo '<button class="btn btn-primary open-encode-modal" data-studentid="'.$row['student_id'].'" data-bs-toggle="modal" data-bs-target="#encodeModal'.$row['encodedgrades_id'].'" title="Encode Grade"><span class="bi bi-plus-circle-fill"></span> Encode</button>';
                                        
                                        
                                        echo '
                                        <div class="modal fade" id="encodeModal'.$row['encodedgrades_id'].'" tabindex="-1" aria-labelledby="encodeModalLabel'.$row['encodedgrades_id'].'" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="encodeModalLabel'.$row['encodedgrades_id'].'">Encode Grade</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="save_grade.php" method="post">
                                                    <div class="form-group">
                                                        <label for="firstQuarter">First Quarter Grade:</label>
                                                        <input type="number" class="form-control" id="firstQuarter" name="firstQuarter" min="0" max="100" value="'.intval($row['quarter1']).'">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="secondQuarter">Second Quarter Grade:</label>
                                                        <input type="number" class="form-control" id="secondQuarter" name="secondQuarter" min="0" max="100" value="'.intval($row['quarter2']).'">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="thirdQuarter">Third Quarter Grade:</label>
                                                        <input type="number" class="form-control" id="thirdQuarter" name="thirdQuarter" min="0" max="100" value="'.intval($row['quarter3']).'">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fourthQuarter">Fourth Quarter Grade:</label>
                                                        <input type="number" class="form-control" id="fourthQuarter" name="fourthQuarter" min="0" max="100" value="'.intval($row['quarter4']).'">
                                                    </div>
                                                    <input type="hidden" id="studentId" name="studentId" value="'.$row['student_id'].'">
                                                    <input type="hidden" id="encodedGradesId" name="encodedGradesId" value="'.$row['encodedgrades_id'].'">
                                                    <button type="submit" class="btn btn-primary">Save Grade</button>
                                                </form>
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
                        } else {
                            echo "Schedule id is not provided in the URL.";
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

    <?php
    include 'footer.php';
    include 'script.php';
  ?>

</body>

</html>