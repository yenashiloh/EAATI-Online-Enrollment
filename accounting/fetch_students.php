<?php
include "config1.php";

if (isset($_POST['grade_level_id'])) {
    $gradeId = intval($_POST['grade_level_id']);
    $sql = "SELECT s.* FROM student s WHERE s.grade_level_id = $gradeId";

    $result = mysqli_query($link, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">Select Student</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['student_id'] . '" data-grade="' . $gradeId . '">' . $row['name'] . '</option>';
        }
    } else {
        echo '<option value="">No students found</option>';
    }
}
