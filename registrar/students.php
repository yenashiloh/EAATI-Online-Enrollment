<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if (!isset($registrar_id)) {
    header('location:../login.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>Students</title>

    <?php
    include 'link.php';
    ?>

</head>
<body class="sidebar-light">
    <?php
    include 'header.php';
    include 'sidebar.php';
    ?>

    <div class="mobile-menu-overlay"></div>
    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">
                <div class="page-header">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="title">
                                <h4>Students</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="registrar_dashboard.php">Menu</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Students
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <div class="pb-20">
                        <div class="row">
                            <?php
                            // Include config file
                            require_once "config1.php";

                            // Modified query to group by grade level and section only, without subject details
                            $sql = "SELECT 
                            sections.section_id,
                            sections.section_name,
                            sections.section_description,
                            sections.sectionCapacity,
                            gradelevel.gradelevel_id,
                            gradelevel.gradelevel_name,
                            COUNT(DISTINCT encodedstudentsubjects.student_id) AS student_count
                            FROM sections 
                            INNER JOIN gradelevel ON gradelevel.gradelevel_id = sections.gradelevel_id
                            LEFT JOIN schedules ON schedules.section_id = sections.section_id
                            LEFT JOIN encodedstudentsubjects ON encodedstudentsubjects.schedule_id = schedules.id
                            GROUP BY sections.section_id, sections.section_name, sections.section_description, 
                                     sections.sectionCapacity, gradelevel.gradelevel_id, gradelevel.gradelevel_name
                            ORDER BY gradelevel.gradelevel_name ASC, sections.section_name ASC";

                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                        <div class="col-lg-3 col-md-6 mb-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h1 class="card-title"><?php echo $row['gradelevel_name']; ?> - <?php echo $row['section_name']; ?></h1>
                                                    <p class="card-text">Enrolled Students: <?php echo $row['student_count']; ?></p>

                                                    <div class="text-center">
                                                        <a href="view_students.php?section_id=<?php echo $row['section_id']; ?>&grade_level_id=<?php echo $row['gradelevel_id']; ?>" class="btn btn-primary" title="View Students">
                                                            <span class="bi bi-eye-fill"></span> View Students
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    // Free result set
                                    mysqli_free_result($result);
                                } else {
                                    echo '<div class="col-lg-12"><div class="alert alert-danger"><em>No records were found.</em></div></div>';
                                }
                            } else {
                                echo '<div class="col-lg-12"><div class="alert alert-danger"><em>Oops! Something went wrong. Please try again later.</em></div></div>';
                            }

                            // Close connection
                            mysqli_close($link);
                            ?>
                        </div> <!-- End of row -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'footer.php';
    ?>
    <!-- End Google Tag Manager (noscript) -->
</body>
</html>
