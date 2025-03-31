<?php
include 'config.php';

session_start();
error_reporting(E_ALL);

$registrar_id = $_SESSION['registrar_id'];
if (!isset($registrar_id)) {
    header('location:../login.php');
    exit; 
} else {
    $error = ""; 
    $msg = ""; 
}
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Add Student</title>

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
                                <h4>Add Student</h4>
                            </div>
                            <nav aria-label="breadcrumb" role="navigation">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="registrar_dashboard.php">Menu</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="schedule.php">Schedule</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Add Student</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <?php
                    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
                        echo "<div class='alert alert-success'>Schedule Deleted Successfully!</div>";
                    }
                    ?>
                    <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#addSubjectModal">
                        <i class="fa fa-plus"></i> Add Student
                    </button>
                    <?php
                    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
                        echo "<div class='alert alert-success'>Student Removed from the class successfully.</div>";
                    }
                    if (isset($_GET['success']) && $_GET['success'] == 1) {
                        echo "<div class='alert alert-success'>Students added successfully!</div>";
                    }
                    ?>
                    <div class="pb-20">
                    <?php
                    require_once "config1.php";
                    if (isset($_GET['id'])) {
                        $schedule_id = $_GET['id'];
                        $schedule_id = mysqli_real_escape_string($link, $schedule_id);
                        
                        $sql = "SELECT encodedstudentsubjects.encoded_id, student.student_id, student.name, 
                                student.dob, users.email 
                                FROM encodedstudentsubjects 
                                INNER JOIN student ON encodedstudentsubjects.student_id = student.student_id 
                                LEFT JOIN users ON student.userId = users.id 
                                WHERE student.isVerified = 2 AND encodedstudentsubjects.schedule_id = $schedule_id";
                        
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table class="data-table table stripe hover nowrap">';
                                echo "<thead><tr><th>Name</th><th>Date of Birth</th><th>Email</th><th>Action</th></tr></thead><tbody>";
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['name']}</td>";
                                    echo "<td>{$row['dob']}</td>";
                                    echo "<td>" . (isset($row['email']) ? $row['email'] : 'No email available') . "</td>";
                                    echo "<td>";
                                    echo '<a href="view_record.php?id=' . $row['student_id'] . '" class="btn p-0 me-1" title="View Record">
                                            <span class="bi bi-eye-fill" style="font-size: 18px;"></span>
                                        </a>';
                                    echo '<a href="#" class="btn p-2" data-toggle="modal" data-target="#deleteModal' . $row['encoded_id'] . '" title="Delete Record">
                                            <span class="bi bi-trash-fill" style="font-size: 18px;"></span>
                                        </a>';

                                    echo '<div class="modal fade" id="deleteModal' . $row['encoded_id'] . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row['encoded_id'] . '" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel' . $row['encoded_id'] . '">Confirm Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">Are you sure you want to delete this record?</div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <a href="delete_encodedstudent.php?id=' . $row['encoded_id'] . '&schedule_id=' . $schedule_id . '" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    echo "</td></tr>";
                                }
                                echo "</tbody></table>";
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later. Error: " . mysqli_error($link);
                        }
                    } else {
                        echo "Schedule id is not provided in the URL.";
                    }
                    mysqli_close($link);
                    ?>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Add Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form method="post" action="encoded.php">
                    <input type="hidden" name="schedule_id" value="<?php echo $id; ?>">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Select</th>
                                    <th>Grade Level</th>
                                    <th>Student Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get the grade level of the current schedule
                                $sql = "SELECT grade_level FROM schedules WHERE id = :schedule_id";
                                $query = $conn->prepare($sql);
                                $query->bindParam(':schedule_id', $id, PDO::PARAM_INT);
                                $query->execute();
                                $schedule_grade_level = $query->fetchColumn();

                                // Get already associated students
                                $sql = "SELECT student_id FROM encodedstudentsubjects WHERE schedule_id = :schedule_id";
                                $query = $conn->prepare($sql);
                                $query->bindParam(':schedule_id', $id, PDO::PARAM_INT);
                                $query->execute();
                                $associated_students = $query->fetchAll(PDO::FETCH_COLUMN);

                                // Get students from the same grade level as the schedule
                                $sql = "SELECT s.*, g.gradelevel_name 
                                        FROM student s 
                                        LEFT JOIN gradelevel g ON s.grade_level_id = g.gradelevel_id
                                        WHERE g.gradelevel_id = :grade_level_id";
                                $query = $conn->prepare($sql);
                                $query->bindParam(':grade_level_id', $schedule_grade_level, PDO::PARAM_INT);
                                $query->execute();
                                $students = $query->fetchAll(PDO::FETCH_ASSOC);

                                if (empty($students) || count($students) == count($associated_students)) {
                                    echo '<tr><td colspan="3" class="text-center">No students available to add to this class.</td></tr>';
                                } else {
                                    foreach ($students as $student) {
                                        if (!in_array($student['student_id'], $associated_students)) {
                                            echo '<tr>';
                                            echo '<td class="text-center"><input class="form-check-input position-static" type="checkbox" name="selected_students[]" value="' . $student['student_id'] . '"></td>';
                                            echo '<td>' . $student['gradelevel_name'] . '</td>';
                                            echo '<td>' . $student['name'] . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
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
