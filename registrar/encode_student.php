<?php
// Your database connection logic
include 'config.php';

// Start session and error reporting
session_start();
error_reporting(E_ALL);

// Check if the user is logged in
$registrar_id = $_SESSION['registrar_id'];
if (!isset($registrar_id)) {
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
                            // Check if the 'deleted' parameter is set and equals to 1
                            if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
                                echo "<div class='alert alert-success'>Student Remove from the class successfully.</div>";
                            }

                            if (isset($_GET['success']) && $_GET['success'] == 1) {
                                // Display a success message
                                echo "<div class='alert alert-success'>Students added successfully!</div>";
                            }
                            ?>
                            <button type="button" class="btn btn-success pull-right" data-bs-toggle="modal"
                                data-bs-target="#addSubjectModal">
                                <i class="fa fa-plus"></i> Add Student
                            </button>
                        <!-- Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to add subject -->
                <form method="post" action="encoded.php">
                    <input type="hidden" name="schedule_id" value="<?php echo $id; ?>">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Grade Level</th>
                                    <th>Student Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch students already associated with the class
                                $sql = "SELECT student_id FROM encodedstudentsubjects WHERE schedule_id = :schedule_id";
                                $query = $conn->prepare($sql);
                                $query->bindParam(':schedule_id', $id, PDO::PARAM_INT);
                                $query->execute();
                                $associated_students = $query->fetchAll(PDO::FETCH_COLUMN);

                                // Fetch all students
                                $sql = "SELECT * FROM student";
                                $query = $conn->prepare($sql);
                                $query->execute();
                                $students = $query->fetchAll(PDO::FETCH_ASSOC);

                                // Display students for selection
                                foreach ($students as $student) {
                                    // Check if the student is already associated with the class
                                    if (!in_array($student['student_id'], $associated_students)) {
                                        echo '<tr>';
                                        echo '<td><input class="form-check-input" type="checkbox" name="selected_students[]" value="' . $student['student_id'] . '"></td>';
                                        echo '<td>' . $student['grade_level'] . '</td>';
                                        echo '<td>' . $student['name'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center"> <!-- Centered div for the button -->
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


                        <?php
                        require_once "config1.php";

                        // Check if the schedule id is provided in the URL
                        if(isset($_GET['id'])) {
                            // Get the id from the URL
                            $schedule_id = $_GET['id'];
                            
                            // Attempt select query execution
                            $sql = "SELECT * FROM encodedstudentsubjects 
                                    INNER JOIN student ON encodedstudentsubjects.student_id = student.student_id 
                                    WHERE student.isVerified = 2 AND encodedstudentsubjects.schedule_id = $schedule_id";
                            
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) > 0){
                                    // Output data of each row
                                    echo '<table class="table datatable">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>Name</th>";
                                    echo "<th>Date of Birth</th>";
                                    echo "<th>Email</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['dob'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>";
                                        echo '<a href="view_record.php?id='.$row['student_id'].'" class="btn btn-info" title="View Record"><span class="bi bi-eye-fill"></span></a>';
                                        echo '  ';
                                        echo '<a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['encoded_id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';

                                        // Delete Modal
                                        echo '
                                            <div class="modal fade" id="deleteModal'.$row['encoded_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['encoded_id'].'" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel'.$row['encoded_id'].'">Confirm Delete</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete this record?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <a href="delete_encodedstudent.php?id='.$row['encoded_id'].'&schedule_id='.$schedule_id.'" class="btn btn-danger">Delete</a>
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