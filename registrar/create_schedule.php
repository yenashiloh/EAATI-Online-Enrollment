<?php

include 'config.php';

session_start();
error_reporting(0);
$registrar_id = $_SESSION['registrar_id'];

if (!isset($registrar_id)) {
    header('location:../login.php');
} else {
    if (isset($_POST['add_schedule'])) {
        $grade_level = $_POST['grade_level'];
        $section_id = $_POST['section'];
        $subject_id = $_POST['subject_name'];
        
        $subject_query = $conn->prepare("SELECT subject_name FROM subjects WHERE subject_id = :subject_id");
        $subject_query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $subject_query->execute();
        $subject_name = $subject_query->fetchColumn();
    
        $teacher_id = $_POST['teacher'];
        $room_id = $_POST['room'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $days = $_POST['days']; 
    
        $sql = "INSERT INTO schedules (grade_level, section_id, subject_name, subject_id, teacher_id, room_id, start_time, end_time, day) 
                VALUES (:grade_level, :section_id, :subject_name, :subject_id, :teacher_id, :room_id, :start_time, :end_time, :day)";
        $query = $conn->prepare($sql);
    
        $success = true;
        foreach ($days as $day) {
            $query->bindParam(':grade_level', $grade_level, PDO::PARAM_STR);
            $query->bindParam(':section_id', $section_id, PDO::PARAM_INT);
            $query->bindParam(':subject_name', $subject_name, PDO::PARAM_STR);
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
            $msg = "Schedule Added Successfully!";
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
		<title>Create Schedule</title>

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
									<h4>Create Schedule</h4>
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
											Create Schedule
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

                <form class="row g-3" method="post" name="add_schedule">
                    
                    <div class="col-md-12">
                        <label for="grade_level" class="form-label">Grade Level</label>
                        <select id="grade_level" class="custom-select col-12 mb-3" name="grade_level" required onchange="loadSections(this.value)">
                            <option selected value="">Select Grade Level</option>
                            <?php
                            $sql = "SELECT * FROM gradelevel ORDER BY gradelevel_name ASC";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['gradelevel_id'] . "'>" . $row['gradelevel_name'] . "</option>";
                            }
                            ?>
                        </select>
                        <div id="no-section-message" class="alert alert-warning mt-2" style="display:none;">
                            No sections available for this grade level.
                        </div>
                    </div>


                        <div class="col-md-12">
                            <label for="section" class="form-label">Section</label>
                            <select id="section" class="custom-select col-12 mb-3" name="section" required>
                                <option selected value="">Select Section</option>
                            <?php
                            $sql = "SELECT * FROM sections";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['section_id'] . "'>" . $row['section_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="subject_name" class="form-label">Subject</label>
                        <select id="subject_name" class="custom-select col-12 mb-3" name="subject_name" required>
                            <option selected>Select Subject</option>
                            <?php
                            $sql = "SELECT * FROM subjects";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['subject_id'] . "'>" . $row['subject_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="teacher" class="form-label">Teacher</label>
                        <select id="teacher" class="custom-select col-12 mb-3" name="teacher" required>
                            <option selected>Select Teacher</option>
                            <?php
                            $sql = "SELECT * FROM users WHERE role = 'teacher'";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['id'] . "'>" . ucfirst($row['first_name']) . " " . ucfirst($row['last_name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="room" class="form-label">Room</label>
                        <select id="room" class="custom-select" name="room" required>
                            <option selected>Select Room</option>
                            <?php
                            $sql = "SELECT * FROM rooms";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['room_id'] . "'>" . $row['room_name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="days" class="form-label mt-3">Select Days</label><br>
                        <?php
                        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                        foreach ($days as $day) {
                            echo '
                            <div class="form-check form-check-inline ">
                                <input type="checkbox"  id="' . strtolower($day) . '" name="days[]" value="' . $day . '" class="form-check-input">
                                <label for="' . strtolower($day) . '" class="form-check-label">' . $day . '</label>
                            </div>';
                        }
                        ?>
                    </div>

                    <div class="col-md-12">
                        <label for="start_time" class="form-label mt-3">Start Time</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                    </div>

                    <div class="col-md-12">
                        <label for="end_time" class="form-label  mt-3">End Time</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                    </div>

                    <div class="d-flex justify-content-start ml-3 mt-3">
                      <button type="submit" class="btn btn-primary mr-2" name="add_schedule">Submit</button>
                      <button type="reset" class="btn btn-secondary">Reset</button>
                  </div>

                </form>
            </div>
          </div>
        </div>

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
    ?>
	</body>

    <script>
    function loadSections(gradeLevel) {
        const sectionDropdown = document.getElementById('section');
        const noSectionMessage = document.getElementById('no-section-message');
        
        sectionDropdown.innerHTML = '<option selected value="">Select Section</option>';
        
        noSectionMessage.style.display = 'none';

        sectionDropdown.disabled = true;
        
        if (!gradeLevel) {
            return;
        }
        
        fetch('get_sections.php?grade_level=' + gradeLevel)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                if (result.count === 0) {
                    noSectionMessage.style.display = 'block';
                    sectionDropdown.disabled = true; 
                } else {
                    result.data.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.section_id;
                        option.textContent = section.section_name;
                        sectionDropdown.appendChild(option);
                    });

                    sectionDropdown.disabled = false; 
                }
            })
            .catch(error => {
                console.error('Error loading sections:', error);
                alert('Failed to load sections. Please try again.');
            });
    }
</script>

</html>
<?php } ?>