<?php

include 'config.php';

session_start();
error_reporting(0);
$registrar_id = $_SESSION['registrar_id'];

if (!isset($registrar_id)) {
    header('location:login.php');
} else {
    if (isset($_POST['add_schedule'])) {
        $grade_level = $_POST['grade_level'];
        $section_id = $_POST['section'];
        $subject_id = $_POST['subject_name'];
        $teacher_id = $_POST['teacher'];
        $room_id = $_POST['room'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $days = $_POST['days']; // This is now an array

        // Validate inputs here...

        // Prepare and execute SQL query for each selected day
        $sql = "INSERT INTO schedules (grade_level, section_id, subject_id, teacher_id, room_id, start_time, end_time, day) 
                VALUES (:grade_level, :section_id, :subject_id, :teacher_id, :room_id, :start_time, :end_time, :day)";
        $query = $conn->prepare($sql);

        $success = true;
        foreach ($days as $day) {
            $query->bindParam(':grade_level', $grade_level, PDO::PARAM_STR);
            $query->bindParam(':section_id', $section_id, PDO::PARAM_INT);
            $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
            $query->bindParam(':room_id', $room_id, PDO::PARAM_INT);
            $query->bindParam(':start_time', $start_time, PDO::PARAM_STR);
            $query->bindParam(':end_time', $end_time, PDO::PARAM_STR);
            $query->bindParam(':day', $day, PDO::PARAM_STR);

            if (!$query->execute()) {
                $success = false;
                break;
            }
        }

        if ($success) {
            $msg = "Schedule Added Successfully";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard</title>
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

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Create Schedule</h5>

            <form class="row g-3" method="post" name="add_schedule">
              <?php if($error){?>
                <div class="alert alert-danger" role="alert">
                  <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                </div>
              <?php } else if($msg){?>
                <div class="alert alert-success" role="alert">
                  <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                </div>
              <?php }?>
              <div class="col-md-12">
                <select id="grade_level" class="form-select" name="grade_level" required>
                  <option selected>Select Grade Level</option>
                  <?php
                    // Assuming you have a table named 'users' with 'role' column as 'teacher'
                    $sql = "SELECT * FROM gradelevel ORDER By gradelevel_name ASC";
                    $result = $conn->query($sql);
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['gradelevel_id'] . "'>" . $row['gradelevel_name'] . "</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-12">
                <select id="section" class="form-select" name="section" required>
                  <option selected>Select Section</option>
                  <?php
                    // Assuming you have a table named 'users' with 'role' column as 'teacher'
                    $sql = "SELECT * FROM sections";
                    $result = $conn->query($sql);
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['section_id'] . "'>" . $row['section_name'] . "</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-12">
                <select id="subject_name" class="form-select" name="subject_name" required>
                  <option selected>Select Subject</option>
                  <?php
                    // Assuming you have a table named 'users' with 'role' column as 'teacher'
                    $sql = "SELECT * FROM subjects";
                    $result = $conn->query($sql);
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['subject_id'] . "'>" . $row['subject_name'] . "</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-12">
                <select id="teacher" class="form-select" name="teacher" required>
                  <option selected>Select Teacher</option>
                  <?php
                    // Assuming you have a table named 'users' with 'role' column as 'teacher'
                    $sql = "SELECT * FROM users WHERE role = 'teacher'";
                    $result = $conn->query($sql);
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-12">
                <select id="room" class="form-select" name="room" required>
                  <option selected>Select Room</option>
                  <?php
                    // Assuming you have a table named 'users' with 'role' column as 'teacher'
                    $sql = "SELECT * FROM rooms";
                    $result = $conn->query($sql);
                    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['room_id'] . "'>" . $row['room_name'] . "</option>";
                    }
                  ?>
                </select>
              </div>

              <div class="col-md-12">
    <label for="days" class="form-label">Select Days</label><br>
    <div class="form-check form-check-inline">
      <input type="checkbox" id="monday" name="days[]" value="Monday" class="form-check-input" onclick="handleSingleCheckbox(this)">
      <label for="monday" class="form-check-label">Monday</label>
    </div>
    <div class="form-check form-check-inline">
      <input type="checkbox" id="tuesday" name="days[]" value="Tuesday" class="form-check-input" onclick="handleSingleCheckbox(this)">
      <label for="tuesday" class="form-check-label">Tuesday</label>
    </div>
    <div class="form-check form-check-inline">
      <input type="checkbox" id="wednesday" name="days[]" value="Wednesday" class="form-check-input" onclick="handleSingleCheckbox(this)">
      <label for="wednesday" class="form-check-label">Wednesday</label>
    </div>
    <div class="form-check form-check-inline">
      <input type="checkbox" id="thursday" name="days[]" value="Thursday" class="form-check-input" onclick="handleSingleCheckbox(this)">
      <label for="thursday" class="form-check-label">Thursday</label>
    </div>
    <div class="form-check form-check-inline">
      <input type="checkbox" id="friday" name="days[]" value="Friday" class="form-check-input" onclick="handleSingleCheckbox(this)">
      <label for="friday" class="form-check-label">Friday</label>
    </div>
    <div class="form-check form-check-inline">
      <input type="checkbox" id="saturday" name="days[]" value="Saturday" class="form-check-input" onclick="handleSingleCheckbox(this)">
      <label for="saturday" class="form-check-label">Saturday</label>
    </div>
    <div class="form-check form-check-inline">
      <input type="checkbox" id="sunday" name="days[]" value="Sunday" class="form-check-input" onclick="handleSingleCheckbox(this)">
      <label for="sunday" class="form-check-label">Sunday</label>
    </div>
  </div>

              <div class="col-md-12">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" class="form-control" id="start_time" name="start_time" required>
              </div>
              <div class="col-md-12">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" class="form-control" id="end_time" name="end_time" required>
              </div>

              <div class="text-center">
                <button type="submit" class="btn btn-primary" name="add_schedule">Submit</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

</main>
<script>
    function handleSingleCheckbox(checkbox) {
        var checkboxes = document.querySelectorAll('input[name="' + checkbox.name + '"]');
        checkboxes.forEach(function(cb) {
            if (cb !== checkbox) {
                cb.checked = false;
            }
        });
    }
</script>
<?php
    include 'footer.php';
    include 'script.php';
?>

</body>

</html>
<?php } ?>
