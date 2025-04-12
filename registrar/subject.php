<?php
session_start();
include 'config.php';

$registrar_id = $_SESSION['registrar_id'] ?? null;
if (!$registrar_id) {
    header('location:login.php');
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT gradelevel_id, gradelevel_name 
        FROM gradelevel 
        ORDER BY CAST(SUBSTRING_INDEX(gradelevel_name, ' ', -1) AS UNSIGNED) ASC
    ");

    $stmt->execute();
    $gradeLevels = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log('Grade Levels: ' . print_r($gradeLevels, true));

    if (empty($gradeLevels)) {
        error_log('No grade levels found in the database');
    }
} catch(PDOException $e) {
    error_log('Database Error Fetching Grade Levels: ' . $e->getMessage());
    $gradeLevels = [];
}

try {
    $stmt = $conn->prepare("SELECT id, first_name, last_name FROM users WHERE role = 'teacher'");
    $stmt->execute();
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log('Teachers: ' . print_r($teachers, true));

    if (empty($teachers)) {
        error_log('No teachers found in the database');
    }
} catch(PDOException $e) {
    error_log('Database Error Fetching Teachers: ' . $e->getMessage());
    $teachers = []; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subjectName = $_POST['subjectName'] ?? '';
    $subjectDescription = $_POST['subjectDescription'] ?? '';
    $gradeLevel = $_POST['gradeLevel'] ?? '';
    $subjectTeacher = $_POST['subjectTeacher'] ?? '';

    try {
        $stmt = $conn->prepare("INSERT INTO subjects (subject_name, subject_description, grade_level_id, teacher_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$subjectName, $subjectDescription, $gradeLevel, $subjectTeacher]);

        header("Location: subjects.php?added=1");
        exit;
    } catch(PDOException $e) {
        $error = "Error adding subject: " . $e->getMessage();
        error_log($error);
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset="utf-8" />
        <title>Grade Level</title>

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
                                    <h4>Grade Level</h4>
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="teacher_dashboard.php">Menu</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Grade Level
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Subject
                        </button>
                        <?php
                        // Success and error messages
                        if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
                            echo "<div class='alert alert-success'>Subject Deleted Successfully!</div>";
                        }
                        if(isset($_GET['added']) && $_GET['added'] == 1){
                            echo "<div class='alert alert-success'>New Subject Added Successfully!</div>";
                        }
                        if(isset($_GET['edited']) && $_GET['edited'] == 1){
                            echo "<div class='alert alert-success'>Updated Successfully!</div>";
                        }
                        ?>

                        <?php
                        // Include config file
                        require_once "config1.php";

                        $sql = "SELECT s.*, u.first_name, u.last_name, g.gradelevel_name 
                            FROM subjects s
                            LEFT JOIN users u ON s.teacher_id = u.id
                            LEFT JOIN gradelevel g ON s.grade_level_id = g.gradelevel_id
                            ORDER BY CAST(SUBSTRING(g.gradelevel_name, 7) AS UNSIGNED) ASC";

                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                            echo '<table class="data-table table stripe hover nowrap">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>#</th>";
                            echo "<th>Teacher</th>";
                            echo "<th>Grade Level</th>";
                            echo "<th>Subject Name</th>";
                            echo "<th>Subject Description</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            $counter = 1; // Initialize counter
                            while ($row = mysqli_fetch_array($result)) {
                                // Query to get teacher info
                                $teacherSql = "SELECT first_name, last_name FROM users WHERE id = " . $row['teacher_id'];
                                $teacherResult = mysqli_query($link, $teacherSql);
                                $teacherRow = mysqli_fetch_array($teacherResult);
                                $teacherName = isset($teacherRow) ? $teacherRow['first_name'] . " " . $teacherRow['last_name'] : "Not Assigned";

                                // Query to get grade level info
                                $gradeLevelSql = "SELECT gradelevel_name FROM gradelevel WHERE gradelevel_id = " . $row['grade_level_id'];
                                $gradeLevelResult = mysqli_query($link, $gradeLevelSql);
                                $gradeLevelRow = mysqli_fetch_array($gradeLevelResult);
                                $gradeLevelName = isset($gradeLevelRow) ? $gradeLevelRow['gradelevel_name'] : "Not Assigned";

                                echo "<tr>";
                                echo "<td>" . $counter . "</td>"; 
                                echo "<td>" . $teacherName . "</td>";
                                echo "<td>" . $gradeLevelName . "</td>";
                                echo "<td>" . $row['subject_name'] . "</td>";
                                echo "<td>" . $row['subject_description'] . "</td>";
                                echo "<td>";
                                echo '<a href="#" class="m-2 edit-btn" data-toggle="modal" data-target="#editModal' . $row['subject_id'] . '" title="Edit Record" data-toggle="tooltip"><span class="bi bi-pencil-fill mr-2" style="font-size: 18px;"></span></a>';

                                // Edit Modal
                                echo '
                                <div class="modal fade" id="editModal' . $row['subject_id'] . '" tabindex="-1" aria-labelledby="editModalLabel' . $row['subject_id'] . '" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel' . $row['subject_id'] . '">Edit Subject</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form to edit subject -->
                                        <form id="editForm' . $row['subject_id'] . '" method="post" action="edit_subject.php">
                                        <input type="hidden" name="subject_id" value="' . $row['subject_id'] . '">
                                        <div class="mb-3">
                                            <label for="editGradeLevel' . $row['subject_id'] . '" class="form-label">Grade Level</label>
                                            <select class="form-control" id="editGradeLevel' . $row['subject_id'] . '" name="gradeLevelId" required>';
                                // Get all grade levels
                                $gradeLevelQuerySql = "SELECT gradelevel_id, gradelevel_name FROM gradelevel  ORDER BY CAST(SUBSTRING_INDEX(gradelevel_name, ' ', -1) AS UNSIGNED) ASC";
                                $gradeLevelQueryResult = mysqli_query($link, $gradeLevelQuerySql);
                                while ($gradeLevelQueryRow = mysqli_fetch_array($gradeLevelQueryResult)) {
                                $selected = ($gradeLevelQueryRow['gradelevel_id'] == $row['grade_level_id']) ? 'selected' : '';
                                echo '<option value="' . $gradeLevelQueryRow['gradelevel_id'] . '" ' . $selected . '>' . $gradeLevelQueryRow['gradelevel_name'] . '</option>';
                                }
                                echo '</select>
                                            <label for="editTeacher' . $row['subject_id'] . '" class="form-label mt-3">Teacher</label>
                                            <select class="form-control" id="editTeacher' . $row['subject_id'] . '" name="teacherId" required>';
                                // Get all teachers
                                $teacherQuerySql = "SELECT id, first_name, last_name FROM users WHERE role = 'teacher'";
                                $teacherQueryResult = mysqli_query($link, $teacherQuerySql);
                                while ($teacherQueryRow = mysqli_fetch_array($teacherQueryResult)) {
                                $selected = ($teacherQueryRow['id'] == $row['teacher_id']) ? 'selected' : '';
                                echo '<option value="' . $teacherQueryRow['id'] . '" ' . $selected . '>' . $teacherQueryRow['first_name'] . ' ' . $teacherQueryRow['last_name'] . '</option>';
                                }
                                echo '</select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editSubjectName' . $row['subject_id'] . '" class="form-label">Subject Name</label>
                                            <input type="text" class="form-control" id="editSubjectName' . $row['subject_id'] . '" name="subjectName" value="' . $row['subject_name'] . '" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editSubjectDescription' . $row['subject_id'] . '" class="form-label">Subject Description</label>
                                            <textarea class="form-control" id="editSubjectDescription' . $row['subject_id'] . '" name="subjectDescription" rows="3" required>' . $row['subject_description'] . '</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                                </div>';

                                echo '<a href="#" data-toggle="modal" data-target="#deleteModal' . $row['subject_id'] . '" title="Delete Record" data-toggle="tooltip">
                                    <span class="bi bi-trash-fill" style="font-size: 18px;"></span>
                                </a>';

                                // Delete Modal
                                echo '
                                <div class="modal fade" id="deleteModal' . $row['subject_id'] . '" tabindex="-1" aria-labelledby="deleteModalLabel' . $row['subject_id'] . '" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel' . $row['subject_id'] . '">Confirm Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this subject?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <a href="delete_subject.php?id=' . $row['subject_id'] . '" class="btn btn-danger">Delete</a>
                                    </div>
                                    </div>
                                </div>
                                </div>';
                                echo "</td>";
                                echo "</tr>";
                                $counter++; // Increment counter
                            }
                            echo "</tbody>";
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                            } else {
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        // Close connection
                        mysqli_close($link);
                        ?>

                  <!-- Modal -->
                  <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                <?php 
                                if(isset($error)){
                                    echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
                                }
                                ?>
                                <!-- Form to add subject -->
                                <form method="post" action="add_subject.php">
                                <div class="mb-3">
                                        <label for="gradeLevel" class="form-label">Grade Level</label>
                                        <select class="form-control" id="gradeLevel" name="gradeLevel" required>
                                            <option value="">Select Grade Level</option>
                                            <?php foreach($gradeLevels as $grade): ?>
                                                <option value="<?php echo $grade['gradelevel_id']; ?>">
                                                    <?php echo htmlspecialchars($grade['gradelevel_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subjectTeacher" class="form-label">Subject Teacher</label>
                                        <select class="form-control" id="subjectTeacher" name="subjectTeacher" required>
                                            <option value="">Select Teacher</option>
                                            <?php foreach($teachers as $teacher): ?>
                                                <option value="<?php echo $teacher['id']; ?>">
                                                    <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subjectName" class="form-label">Subject Name</label>
                                        <input type="text" class="form-control" id="subjectName" name="subjectName" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="subjectDescription" class="form-label">Subject Description</label>
                                        <textarea class="form-control" id="subjectDescription" name="subjectDescription" rows="3" required></textarea>
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
            </body>
</html>