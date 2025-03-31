<?php

include 'config.php';

session_start();

$teacher_id = $_SESSION['teacher_id'];

if (!isset($teacher_id)) {
	header('location:../login.php');
	exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset="utf-8" />
        <title>View Students</title>

        <?php
        include 'link.php';
        ?>
    </head>
    <body class="sidebar-light">
        <?php
        include 'header.php';
        include 'sidebar.php';

        // Fetch user details for editing
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM schedules WHERE id = :id";
            $query = $conn->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $student = $query->fetch(PDO::FETCH_ASSOC);
        }
        ?>

        <div class="mobile-menu-overlay"></div>
        <div class="main-container">
            <div class="pd-ltr-20 xs-pd-20-10">
                <div class="min-height-200px">
                    <div class="page-header">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="title">
                                    <h4>View Students</h4>
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="teacher_dashboard.php">Menu</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="students.php">Class List</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            View Students
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <div class="pd-20">
                    <?php
                        require_once "config1.php";
                        // Check for success message in URL
                        if (isset($_GET['status_updated']) && isset($_GET['new_status'])) {
                            $new_status = htmlspecialchars($_GET['new_status']);
                            echo "<div id='alertContainer' class='container mt-3'>
                                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                        Status successfully changed to \"{$new_status}\"
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                            <span aria-hidden='true'>×</span>
                                        </button>
                                    </div>
                                </div>";
                        }
                        // Check if the section_id and grade_level_id are provided in the URL
                        if (isset($_GET['section_id']) && isset($_GET['grade_level_id'])) {
                            $section_id = intval($_GET['section_id']);
                            $grade_level_id = intval($_GET['grade_level_id']);
                            
                            // Get section and grade level details
                            $section_sql = "SELECT s.*, g.gradelevel_name 
                            FROM sections s
                            JOIN gradelevel g ON g.gradelevel_id = s.gradelevel_id
                            WHERE s.section_id = $section_id 
                            AND g.gradelevel_id = $grade_level_id";
                            $section_result = mysqli_query($link, $section_sql);
                            $section_row = mysqli_fetch_assoc($section_result);
                            
                            if ($section_row) {
                                ?>
                                <h4 class="h4 mb-4"><?php echo htmlspecialchars($section_row['gradelevel_name']); ?> - Section <?php echo htmlspecialchars($section_row['section_name']); ?></h4>
                                <div class="pb-20">
                                    <?php
                                    // Method 1: Get students directly from encoded subjects with matching schedules
                                    $sql = "SELECT DISTINCT s.student_id, s.name, s.dob, s.isVerified, u.email 
                                        FROM student s
                                        LEFT JOIN users u ON s.userId = u.id
                                        JOIN encodedstudentsubjects ess ON s.student_id = ess.student_id
                                        JOIN schedules sch ON ess.schedule_id = sch.id
                                        WHERE sch.section_id = $section_id
                                        ORDER BY s.name";
                                    
                                    if ($result = mysqli_query($link, $sql)) {
                                        if (mysqli_num_rows($result) > 0) {
                                            echo '<table class="data-table table stripe hover nowrap">';
                                            echo "<thead><tr>
                                                    <th>No.</th>
                                                    <th>Name</th>
                                                    <th>Date of Birth</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr></thead><tbody>";
                                            $counter = 1;
                                            while ($row = mysqli_fetch_array($result)) {
                                                echo "<tr>";
                                                echo "<td>" . $counter++ . "</td>";
                                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                echo "<td>" . ($row['isVerified'] == 1 ? 'Verified' : 'Not Verified') . "</td>";
                                                echo "<td>";
                                        
                                                echo '<a href="view_record.php?id='.$row['student_id'].'" class="btn p-0 me-1" title="View Record"><span class="bi bi-eye-fill" style="font-size: 20px;"></span></a>';
        
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            echo "</tbody></table>";
                                            mysqli_free_result($result);
                                        } else {
                                            // Debug output - only visible in page source code
                                            echo "<!-- Query executed: $sql -->";
                                            echo '<div class="alert alert-danger"><em>No students enrolled in this section.</em></div>';
                                        }
                                    } else {
                                        echo "Oops! Something went wrong. Please try again later. Error: " . mysqli_error($link);
                                    }
                                    ?>
                                </div>
                                <?php
                            } else {
                                echo "<div class='alert alert-warning'>Section or Grade Level not found.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Section ID or Grade Level ID is not provided in the URL.</div>";
                        }
                        mysqli_close($link);
                        ?>
                    </div>
                </div>
            </div>
            <?php
            include 'footer.php';
            ?>
        </div>
    </body>
</html>
