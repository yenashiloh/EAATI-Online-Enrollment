<?php

include 'config.php';

session_start();

$teacher_id = $_SESSION['teacher_id'];

if(!isset($teacher_id)){
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

    <style>
        /* Additional styling for cards */
        .card {
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
        }
    </style>
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
            <?php
            // Include config file
            require_once "config1.php";

            // Attempt select query execution
            $sql = "SELECT schedules.*,subjects.subject_name,sections.section_name,rooms.room_name, CONCAT(users.first_name, ' ', users.last_name) AS teacher_name, COUNT(encodedstudentsubjects.student_id) AS student_count FROM schedules 
            INNER JOIN users ON schedules.teacher_id = users.id
            INNER JOIN sections on sections.section_id = schedules.section_id
            INNER JOIN subjects ON subjects.subject_id = schedules.subject_id
            INNER JOIN gradelevel ON gradelevel.gradelevel_id = schedules.grade_level
            INNER JOIN rooms ON rooms.room_id = schedules.room_id
            LEFT JOIN encodedstudentsubjects ON encodedstudentsubjects.schedule_id = schedules.id
            WHERE schedules.teacher_id = $teacher_id
            GROUP BY schedules.id";
            if($result = mysqli_query($link, $sql)){
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_array($result)){
                        ?>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title" hidden>#<?php echo $row['id']; ?></h5>
                                    <h1 class="card-title">Grade <?php echo $row['grade_level']; ?> - <?php echo $row['section_name']; ?></h1>
                                    <p class="card-text">Subject: <?php echo $row['subject_name']; ?></p>
                                    <p class="card-text">Enrolled Students: <?php echo $row['student_count']; ?></p>
                                    
                                    <div class="text-center">
                                        <a href="encode_studentgrade.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" title="Encode">
                                            <span class="bi bi-file-earmark-spreadsheet-fill"></span>Encode Student Grade
                                        </a>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    // Free result set
                    mysqli_free_result($result);
                } else{
                    echo '<div class="col-lg-12"><div class="alert alert-danger"><em>No records were found.</em></div></div>';
                }
            } else{
                echo '<div class="col-lg-12"><div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div></div>';
            }

            // Close connection
            mysqli_close($link);
            ?>
        </div>
    </section>

</main><!-- End #main -->

<?php
    include 'footer.php';
    include 'script.php';
?>

</body>

</html>
