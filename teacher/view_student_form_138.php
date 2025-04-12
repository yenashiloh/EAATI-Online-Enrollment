<?php
include 'config.php';

session_start();

$teacher_id = $_SESSION['teacher_id'];

if(!isset($teacher_id)){
   header('location:../login.php');
   exit;
}

// Get section_id from URL parameter
$section_id = isset($_GET['section_id']) ? $_GET['section_id'] : 0;

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>	Form 138</title>

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
                                    <h4>Form 138</h4>
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="teacher_dashboard.php">Menu</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="form138.php">Form 138</a>
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
                            <h4 class="h4 mb-1">Enrolled Students List</h4>
                        </div>

                        <?php
                        if(isset($_GET['verified']) && $_GET['verified'] == 1){
                            echo "<div class='alert alert-success'>Record verified successfully.</div>";
                        }
                        ?>
                        <div class="pb-20">
                            <?php
                            require_once "config1.php";

                            // Modified query to filter by section_id from the URL parameter
                            $sql = "SELECT student.*, student.name, users.email, student.dob 
                                    FROM student 
                                    INNER JOIN users ON student.userId = users.id 
                                    INNER JOIN encodedstudentsubjects ON encodedstudentsubjects.student_id = student.student_id
                                    INNER JOIN schedules ON encodedstudentsubjects.schedule_id = schedules.id
                                    WHERE schedules.section_id = $section_id
                                    GROUP BY student.student_id
                                    ORDER BY student.name ASC";
                            
                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    echo '<table class="data-table table stripe hover nowrap">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>No.</th>"; 
                                    echo "<th>Name</th>";
                                    echo "<th>Date of Birth</th>";
                                    echo "<th>Email</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";

                                    $counter = 1; 
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $counter . "</td>"; 
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['dob'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>";
                                        echo '<a href="view_card.php?id=' . $row['student_id'] . '&section_id=' . $section_id . '" class="btno" title="View Student Details" data-bs-toggle="tooltip" data-bs-placement="top">
                                        <span class="bi bi-eye-fill" style="font-size:20px;"></span>
                                      </a>';
                                        echo '  ';
                                        echo "</td>";
                                        echo "</tr>";

                                        $counter++;
                                    }

                                    echo "</tbody>";
                                    echo "</table>";
                                    mysqli_free_result($result);
                                } else {
                                    echo '<div class="alert alert-danger"><em>No students enrolled in this section.</em></div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger"><em>Oops! Something went wrong. Error: ' . mysqli_error($link) . '</em></div>';
                            }
                            mysqli_close($link);
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

		<?php
         include 'footer.php';
       ?>

	</body>
</html>