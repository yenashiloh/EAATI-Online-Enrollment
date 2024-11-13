<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if(!isset($registrar_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
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
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Schedule Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Schedule</li>
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
                            echo "<div class='alert alert-success'>Schedule deleted successfully.</div>";
                        }
                        ?>

                        <a href="create_schedule.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Schedule</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config1.php";
                    

                    // Attempt select query execution
                    $sql = "SELECT schedules.*,subjects.subject_name,sections.section_name,rooms.room_name,gradelevel.gradelevel_name, CONCAT(users.first_name, ' ', users.last_name) AS teacher_name FROM schedules 
                    INNER JOIN users ON schedules.teacher_id = users.id
                    INNER JOIN sections on sections.section_id = schedules.section_id
                    INNER JOIN subjects ON subjects.subject_id = schedules.subject_id
                    INNER JOIN gradelevel ON gradelevel.gradelevel_id = schedules.grade_level
                    INNER JOIN rooms ON rooms.room_id = schedules.room_id
                    ORDER BY gradelevel_name ASC";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
                                echo "<thead>";
                                    echo "<tr>";
                                        
                                        echo "<th>Grade Level and Section</th>";
                                        echo "<th>Room</th>";
                                        echo "<th>Subject Name</th>";
                                        echo "<th>Teacher</th>";
                                        echo "<th>Day</th>";
                                        echo "<th>Start Time</th>";
                                        echo "<th>End Time</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        
                                        echo "<td>".$row['gradelevel_name'] ." - ".$row['section_name']. "</td>";
                                        echo "<td>" . $row['room_name'] . "</td>";
                                        echo "<td>" . $row['subject_name'] . "</td>";
                                        echo "<td>" . $row['teacher_name'] . "</td>";
                                        echo "<td>" . $row['day'] . "</td>";
                                        echo "<td>" . $row['start_time'] . "</td>";
                                        echo "<td>" . $row['end_time'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="read.php?id='. $row['id'] .'" class="r-2 btn btn-info" title="View Record" data-toggle="tooltip"><span class="bi bi-eye-fill"></span></a>';
                                            echo '<a href="edit_schedule.php?id='. $row['id'] .'" class="m-2 btn btn-secondary" title="Update Record" data-toggle="tooltip"><span class="bi bi-pencil-fill"></span></a>';
                                            echo '<a href="#" class="r-2 btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';

                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['id'].'">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to delete this schedule?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <a href="delete.php?id='.$row['id'].'" class="btn btn-danger">Delete</a>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>';
                                            echo '<a href="encode_student.php?id='.$row['id'].'" class="m-2 btn btn-primary" title="Add Student"><span class="bi bi-plus-square-fill"></span></a>';
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

<?php
    include 'footer.php';
    include 'script.php';
?>

</body>

</html>
