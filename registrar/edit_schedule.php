<?php

include 'config.php';

session_start();
error_reporting(0);
$registrar_id = $_SESSION['registrar_id'];

if (!isset($registrar_id)) {
    header('location:../login.php');
} else {
    if (isset($_POST['edit_schedule'])) {

        $schedule_id = $_POST['schedule_id'];
        $grade_level = $_POST['grade_level'];
        $section_id = $_POST['section'];
        $subject_id = $_POST['subject_name'];
        $teacher_id = $_POST['teacher_id'];
        $room_id = $_POST['room'];
        $days = implode(',', $_POST['days']);
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $sql = "UPDATE schedules 
                SET 
                grade_level = :grade_level,
                section_id = :section_id,
                subject_id = :subject_id, 
                teacher_id = :teacher_id, 
                room_id = :room_id,
                day = :day, 
                start_time = :start_time, 
                end_time = :end_time 
                WHERE id = :schedule_id";
        $query = $conn->prepare($sql);
        $query->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
        $query->bindParam(':grade_level', $grade_level, PDO::PARAM_INT);
        $query->bindParam(':section_id', $section_id, PDO::PARAM_INT);
        $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $query->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
        $query->bindParam(':room_id', $room_id, PDO::PARAM_INT);
        $query->bindParam(':day', $days, PDO::PARAM_STR);
        $query->bindParam(':start_time', $start_time, PDO::PARAM_STR);
        $query->bindParam(':end_time', $end_time, PDO::PARAM_STR);

        if ($query->execute()) {
            $msg = "Schedule Updated Successfully!";
        } else {
            $error = "Something went wrong. Please try again";
        }
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset="utf-8" />
        <title>Edit Schedule</title>

        <?php
        include 'link.php';
        ?>

    </head>
    <body class="sidebar-light">
        <?php

        include 'header.php';
        include 'sidebar.php';

        // Fetch schedule details for editing
        if (isset($_GET['id'])) {
            $schedule_id = $_GET['id'];
            $sql = "SELECT * FROM schedules WHERE id = :schedule_id";
            $query = $conn->prepare($sql);
            $query->bindParam(':schedule_id', $schedule_id, PDO::PARAM_INT);
            $query->execute();
            $schedule = $query->fetch(PDO::FETCH_ASSOC);
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
                                    <h4>Edit Schedule</h4>
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="teacher_dashboard.php">Menu</a>
                                        </li>
                                        <li class="breadcrumb-item">
                                            <a href="schedule.php">Schedule</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Edit Schedule
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                        <?php if ($error) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlentities($error); ?>
                            </div>
                        <?php } elseif ($msg) { ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlentities($msg); ?>
                            </div>
                        <?php } ?>

                        <form class="row g-3" method="post" name="edit_schedule">

                            <input type="hidden" name="schedule_id" value="<?php echo $schedule['id']; ?>">
                            <div class="col-md-12">
                                <select id="grade_level" class="custom-select col-12 mb-3" name="grade_level" required>
                                    <option value="" selected>Select Grade Level</option>
                                    <?php
                                    $sql = "SELECT * FROM gradelevel";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($row['gradelevel_id'] == $schedule['grade_level']) ? "selected" : "";
                                        echo "<option value='" . $row['gradelevel_id'] . "' $selected>" . $row['gradelevel_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <select id="section" class="custom-select col-12 mb-3" name="section" required>
                                    <option value="" selected>Select Section</option>
                                    <?php
                                    $sql = "SELECT * FROM sections";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($row['section_id'] == $schedule['section_id']) ? "selected" : "";
                                        echo "<option value='" . $row['section_id'] . "' $selected>" . $row['section_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <select id="subject_name" class="custom-select col-12 mb-3" name="subject_name" required>
                                    <option value="" selected>Select Subject</option>
                                    <?php
                                    $sql = "SELECT * FROM subjects";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($row['subject_id'] == $schedule['subject_id']) ? "selected" : "";
                                        echo "<option value='" . $row['subject_id'] . "' $selected>" . $row['subject_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <select id="teacher_id" class="custom-select col-12 mb-3" name="teacher_id" required>
                                    <option selected>Select Teacher</option>
                                    <?php
                                    $sql = "SELECT * FROM users WHERE role = 'teacher'";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($row['id'] == $schedule['teacher_id']) ? "selected" : "";
                                        echo "<option value='" . $row['id'] . "' $selected>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <select id="room" class="custom-select col-12 mb-3" name="room" required>
                                    <option value="" selected>Select Room</option>
                                    <?php
                                    $sql = "SELECT * FROM rooms";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                        $selected = ($row['room_id'] == $schedule['room_id']) ? "selected" : "";
                                        echo "<option value='" . $row['room_id'] . "' $selected>" . $row['room_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php

                            $schedule_days = explode(',', $schedule['day']);
                            $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            ?>
                            <div class="col-md-12">
                                <label for="days" class="form-label">Select Days</label><br>
                                <?php
                                foreach ($daysOfWeek as $day) {
                                    $checked = in_array($day, $schedule_days) ? 'checked' : '';
                                    echo "<div class='form-check form-check-inline'>
                                            <input type='checkbox' id='$day' name='days[]' value='$day' class='form-check-input' $checked onchange='handleCheckboxSelection(this)'>
                                            <label for='$day' class='form-check-label'>$day</label>
                                        </div>";
                                }
                                ?>
                            </div>
                            <div class="col-md-12">
                                <label for="start_time" class="form-label mt-3">Start Time</label>
                                <select class="form-control" id="start_time" name="start_time" required>
                                    <option value="">Select Start Time</option>
                                    <?php
                                    $selectedStartTime = isset($schedule['start_time']) ? date("H:i", strtotime($schedule['start_time'])) : '';

                                    $start = strtotime("07:00 AM");
                                    $end = strtotime("04:00 PM");

                                    while ($start <= $end) {
                                        $timeValue = date("H:i", $start);
                                        $timeDisplay = date("h:i A", $start);
                                        $selected = ($selectedStartTime == $timeValue) ? 'selected' : '';
                                        echo '<option value="' . $timeValue . '" ' . $selected . '>' . $timeDisplay . '</option>';
                                        $start = strtotime("+30 minutes", $start);
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="end_time" class="form-label mt-3">End Time</label>
                                <select class="form-control" id="end_time" name="end_time" required>
                                    <option value="">Select End Time</option>
                                    <?php
                                    $selectedEndTime = isset($schedule['end_time']) ? date("H:i", strtotime($schedule['end_time'])) : '';

                                    $start = strtotime("07:30 AM");
                                    $end = strtotime("04:30 PM");

                                    while ($start <= $end) {
                                        $timeValue = date("H:i", $start);
                                        $timeDisplay = date("h:i A", $start);
                                        $selected = ($selectedEndTime == $timeValue) ? 'selected' : '';
                                        echo '<option value="' . $timeValue . '" ' . $selected . '>' . $timeDisplay . '</option>';
                                        $start = strtotime("+30 minutes", $start);
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-start ml-3 mt-3">
                                <button type="submit" class="btn btn-primary mr-2" name="edit_schedule">Update</button>
                                <a href="schedule.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function handleCheckboxSelection(checkbox) {
                    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    checkboxes.forEach(function (cb) {
                        if (cb !== checkbox) {
                            cb.checked = false;
                        }
                    });
                }

                document.getElementById("start_time").addEventListener("change", function () {
                    let startTime = this.value;
                    let endTimeSelect = document.getElementById("end_time");

                    // Clear previous options
                    endTimeSelect.innerHTML = '<option value="">Select End Time</option>';

                    if (startTime) {
                        let startHour = parseInt(startTime.split(":")[0]);
                        let startMinute = parseInt(startTime.split(":")[1]);
                        let currentTime = new Date();
                        currentTime.setHours(startHour, startMinute);

                        for (let i = 0; i < 18; i++) { // Generate next 9 hours of options
                            currentTime.setMinutes(currentTime.getMinutes() + 30);
                            let formattedTime = currentTime.toTimeString().slice(0, 5); // Get HH:mm format
                            let displayTime = currentTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });

                            let option = document.createElement("option");
                            option.value = formattedTime;
                            option.textContent = displayTime;
                            endTimeSelect.appendChild(option);
                        }

                        endTimeSelect.disabled = false;
                    } else {
                        endTimeSelect.disabled = true;
                    }
                });
            </script>

            <?php
            include 'footer.php';
            ?>
        </body>
    </html>
<?php } ?>
