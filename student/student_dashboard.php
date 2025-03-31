<?php
session_start(); 

include 'config.php';

$student_id = $_SESSION['student_id'];

if (!isset($student_id)) {
  header('location:login.php');
  exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Home</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="../asset/img/logo.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="../asset/img/logo.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="../asset/img/logo.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="../vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="../vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="src/plugins/cropperjs/dist/cropper.css"
		/>
		<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->
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
									<h4>Student Profile</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="student_dashboard.php">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Student Profile
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
							<div class="pd-20 card-box height-100-p">
              <?php
                $conn = mysqli_connect("localhost", "root", "", "enrollment");
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // First get user data
                $sql_user = "SELECT first_name, last_name, email, contact_number FROM users WHERE id = $student_id";
                $result_user = mysqli_query($conn, $sql_user);
                $user_data = mysqli_fetch_assoc($result_user);

                // Then get student data
                $sql_student = "SELECT image_path, grade_level_id, dob, pob, 
                                student_house_number, student_street, student_barangay, 
                                student_municipality, gender, guardian 
                                FROM student 
                                WHERE userId = $student_id";
                $result_student = mysqli_query($conn, $sql_student);
                $student_data = mysqli_fetch_assoc($result_student);
                ?>
              <div class="profile-photo text-center">
                  <!-- Profile Picture -->
                  <img src="<?php echo !empty($student_data['image_path']) ? '../' . $student_data['image_path'] : '../asset/img/user.png'; ?>" 
                      alt="Profile Picture" 
                      class="avatar-photo rounded-circle" 
                      width="150" 
                      height="150" 
                      data-toggle="modal" 
                      data-target="#modal" 
                      style="cursor:pointer;"
                      onerror="this.src='../asset/img/user.png';">

                    <!-- Modal -->
                    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body pd-5">
                                    <div class="img-container text-center">
                                    <img id="image" 
                                    src="<?php echo !empty($student_data['image_path']) ? '../' . $student_data['image_path'] : '../asset/img/user.png'; ?>" 
                                    alt="Profile Picture" 
                                    class="img-fluid" 
                                    onerror="this.src='../asset/img/user.png';">

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Name and Grade -->
                    <h5 class="text-center h5 mb-0">
                        <?php echo $user_data['first_name'] . ' ' . $user_data['last_name']; ?>
                    </h5>
                    <?php if (!empty($student_data['grade_level_id'])) { ?>
                        <p class="text-center text-muted font-14 mb-4">
                            Grade <?php echo $student_data['grade_level_id']; ?>
                        </p>
                    <?php } ?>
                </div>

                <div class="profile-info">
                    <h5 class="mb-20 h5 text-blue mt-4">Personal Information</h5>
                    <ul>
                        <!-- Email from users table -->
                        <?php if (!empty($user_data["email"])) { ?>
                            <li>
                                <span>Email Address:</span>
                                <?php echo $user_data["email"]; ?>
                            </li>
                        <?php } ?>

                        <!-- Contact number from users table -->
                        <?php if (!empty($user_data["contact_number"])) { ?>
                            <li>
                                <span>Contact Number:</span>
                                <?php echo $user_data["contact_number"]; ?>
                            </li>
                        <?php } ?>
                        
                        <!-- Student information -->
                        <?php if (!empty($student_data["dob"])) { ?>
                            <li>
                                <span>Date of Birth:</span>
                                <?php echo $student_data["dob"]; ?>
                            </li>
                        <?php } ?>
                        
                        <?php if (!empty($student_data["pob"])) { ?>
                            <li>
                                <span>Place of Birth:</span>
                                <?php echo $student_data["pob"]; ?>
                            </li>
                        <?php } ?>
                        
                        <?php 
                        if (!empty($student_data["student_house_number"]) || !empty($student_data["student_street"]) || 
                            !empty($student_data["student_barangay"]) || !empty($student_data["student_municipality"])) { 
                        ?>
                            <li>
                                <span>Address:</span>
                                <?php 
                                $address_parts = array_filter([
                                    $student_data["student_house_number"],
                                    $student_data["student_street"],
                                    $student_data["student_barangay"],
                                    $student_data["student_municipality"]
                                ]);
                                echo implode(', ', $address_parts);
                                ?>
                            </li>
                        <?php } ?>
                        
                        <?php if (!empty($student_data["gender"])) { ?>
                            <li>
                                <span>Gender:</span>
                                <?php echo $student_data["gender"]; ?>
                            </li>
                        <?php } ?>
                        
                        <?php if (!empty($student_data["guardian"])) { ?>
                            <li>
                                <span>Guardian:</span>
                                <?php echo $student_data["guardian"]; ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php mysqli_close($conn); ?>
							</div>
						</div>
						<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
							<div class="card-box height-100-p overflow-hidden">
								<div class="profile-tab height-100-p">
									<div class="tab height-100-p">
										<ul class="nav nav-tabs customtab" role="tablist">
											<li class="nav-item">
												<a
													class="nav-link active"
													data-toggle="tab"
													href="#timeline"
													role="tab"
													>Parent/Guardian</a
												>
											</li>
										</ul>
										<div class="tab-content">
											<!-- Parent/Guardian Tab start -->
											<div class="tab-pane fade show active" id="timeline" role="tabpanel">
                        <?php
                        $conn = mysqli_connect("localhost", "root", "", "enrollment");

                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        $userId = $_SESSION['student_id'];
                        $sql_student = "SELECT student_id FROM student WHERE userId = ?";
                        $stmt_student = mysqli_prepare($conn, $sql_student);

                        if ($stmt_student) {
                            mysqli_stmt_bind_param($stmt_student, "i", $userId);
                            mysqli_stmt_execute($stmt_student);
                            $result_student = mysqli_stmt_get_result($stmt_student);

                            if (mysqli_num_rows($result_student) > 0) {
                                $row_student = mysqli_fetch_assoc($result_student);
                                $student_id = $row_student['student_id'];

                                $sql = "SELECT fi.father_name, fi.telephone_father, fi.houseNo_father, fi.street_father, 
                                            fi.barangay_father, fi.municipality_father,
                                            mi.mother_name, mi.telephone_mother, mi.houseNo_mother, mi.street_mother, 
                                            mi.barangay_mother, mi.municipality_mother
                                        FROM student s
                                        LEFT JOIN father_information fi ON s.student_id = fi.student_id
                                        LEFT JOIN mother_information mi ON s.student_id = mi.student_id
                                        WHERE s.student_id = ?";

                                $stmt = mysqli_prepare($conn, $sql);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "i", $student_id);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);

                                    if (mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                     ?>
                                        <div class="profile-info">
                                            <h5 class="mb-20 h5 text-blue mt-3">Father Information</h5>
                                            <div class="row">
                                                <div class="col-12">
                                                    <p style="font-size:14px;"><strong>Father Name:</strong> <?php echo htmlspecialchars($row["father_name"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>House Number:</strong> <?php echo htmlspecialchars($row["houseNo_father"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>Street:</strong> <?php echo htmlspecialchars($row["street_father"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>Barangay:</strong> <?php echo htmlspecialchars($row["barangay_father"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>Municipality:</strong> <?php echo htmlspecialchars($row["municipality_father"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>Contact Number:</strong> <?php echo htmlspecialchars($row["telephone_father"] ?? ''); ?></p>
                                                </div>
                                            </div>

                                            <h5 class="mb-20 h5 text-blue mt-3">Mother Information</h5>
                                            <div class="row">
                                                <div class="col-12">
                                                    <p style="font-size:14px;"><strong>Mother Name:</strong> <?php echo htmlspecialchars($row["mother_name"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>House Number:</strong> <?php echo htmlspecialchars($row["houseNo_mother"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>Street:</strong> <?php echo htmlspecialchars($row["street_mother"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>Barangay:</strong> <?php echo htmlspecialchars($row["barangay_mother"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>Municipality:</strong> <?php echo htmlspecialchars($row["municipality_mother"] ?? ''); ?></p>
                                                    <p style="font-size:14px;"><strong>Contact Number:</strong> <?php echo htmlspecialchars($row["telephone_mother"] ?? ''); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    } else {
                                        echo "<p>No parent information found for student ID: " . $student_id . "</p>";
                                    }
                                    mysqli_stmt_close($stmt);
                                } else {
                                    echo "<p>Error preparing the statement: " . mysqli_error($conn) . "</p>";
                                }
                            } else {
                              echo '<p class="mt-3 ml-3">No student information has been added yet.</p>';
                            }
                            mysqli_stmt_close($stmt_student);
                        } else {
                            echo "<p>Error preparing the student query: " . mysqli_error($conn) . "</p>";
                        }

                        mysqli_close($conn);
                        ?>
                    </div>
											<!-- Parent/Guardian Tab End -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
        <!-- Week Days Schedule Start-->
        <div class="card-box height-100-p">
        <div class="row justify-content-center">
          <div class="col-xl-2 text-center">
            <table class="table  mt-4">
              <thead class="table-dark">
                <tr>
                  <th>Monday</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $conn = mysqli_connect("localhost", "root", "", "enrollment");

                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }

              $sql_monday = "SELECT subjects.subject_name FROM encodedstudentsubjects
              INNER JOIN student ON encodedstudentsubjects.student_id = student.student_id
              INNER JOIN schedules ON encodedstudentsubjects.schedule_id = schedules.id
              INNER JOIN subjects ON schedules.subject_id = subjects.subject_id
              WHERE student.student_id = $student_id AND schedules.day = 'Monday'";
                $result_monday = mysqli_query($conn, $sql_monday);

                if (mysqli_num_rows($result_monday) > 0) {
                  while ($row_monday = mysqli_fetch_assoc($result_monday)) {
                    echo '<tr>';
                    echo '<td>' . $row_monday["subject_name"] . '</td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="7">No schedule for Monday</td></tr>';
                }

                mysqli_close($conn);
                ?>


              </tbody>
            </table>

          </div>
          <div class="col-xl-2 text-center">
            <table class="table  mt-4">
              <thead class="table-dark">
                <tr>
                  <th>Tuesday</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $conn = mysqli_connect("localhost", "root", "", "enrollment");

                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }

                // SQL query to fetch schedule for Monday
                $sql_tuesday = "SELECT subjects.subject_name FROM encodedstudentsubjects
              INNER JOIN student ON encodedstudentsubjects.student_id = student.student_id
              INNER JOIN schedules ON encodedstudentsubjects.schedule_id = schedules.id
              INNER JOIN subjects ON schedules.subject_id = subjects.subject_id
              WHERE student.student_id = $student_id AND schedules.day = 'Tuesday'";
                $result_tuesday = mysqli_query($conn, $sql_tuesday);

                if (mysqli_num_rows($result_tuesday) > 0) {
                  // Output data of each row
                  while ($row_monday = mysqli_fetch_assoc($result_tuesday)) {
                    echo '<tr>';
                    echo '<td>' . $row_monday["subject_name"] . '</td>';
                    // Add similar code for other days
                    echo '</tr>';
                  }
                } else {
                  // No schedule for Monday
                  echo '<tr><td colspan="7">No schedule for Tuesday</td></tr>';
                }

                mysqli_close($conn);
                ?>


              </tbody>
            </table>

          </div>

          <div class="col-xl-2 text-center">
            <table class="table  mt-4">
              <thead class="table-dark">
                <tr>
                  <th>Wednesday</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Connect to your database (replace with actual database credentials)
                $conn = mysqli_connect("localhost", "root", "", "enrollment");

                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }

                // SQL query to fetch schedule for Monday
                $sql_wednesday = "SELECT subjects.subject_name FROM encodedstudentsubjects
              INNER JOIN student ON encodedstudentsubjects.student_id = student.student_id
              INNER JOIN schedules ON encodedstudentsubjects.schedule_id = schedules.id
              INNER JOIN subjects ON schedules.subject_id = subjects.subject_id
              WHERE student.student_id = $student_id AND schedules.day = 'Wednesday'";
                $result_wednesday = mysqli_query($conn, $sql_wednesday);

                if (mysqli_num_rows($result_wednesday) > 0) {
                  // Output data of each row
                  while ($row_monday = mysqli_fetch_assoc($result_wednesday)) {
                    echo '<tr>';
                    echo '<td>' . $row_monday["subject_name"] . '</td>';
                    // Add similar code for other days
                    echo '</tr>';
                  }
                } else {
                  // No schedule for Monday
                  echo '<tr><td colspan="7">No schedule for Wednesday</td></tr>';
                }

                mysqli_close($conn);
                ?>


              </tbody>
            </table>

          </div>


          <div class="col-xl-2 text-center">
            <table class="table  mt-4">
              <thead class="table-dark">
                <tr>
                  <th>Thursday</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Connect to your database (replace with actual database credentials)
                $conn = mysqli_connect("localhost", "root", "", "enrollment");

                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }

                // SQL query to fetch schedule for Monday
                $sql_thursday = "SELECT subjects.subject_name FROM encodedstudentsubjects
              INNER JOIN student ON encodedstudentsubjects.student_id = student.student_id
              INNER JOIN schedules ON encodedstudentsubjects.schedule_id = schedules.id
              INNER JOIN subjects ON schedules.subject_id = subjects.subject_id
              WHERE student.student_id = $student_id AND schedules.day = 'Thursday'";
                $result_thursday = mysqli_query($conn, $sql_thursday);

                if (mysqli_num_rows($result_thursday) > 0) {
                  // Output data of each row
                  while ($row_monday = mysqli_fetch_assoc($result_thursday)) {
                    echo '<tr>';
                    echo '<td>' . $row_monday["subject_name"] . '</td>';
                    // Add similar code for other days
                    echo '</tr>';
                  }
                } else {
                  // No schedule for Monday
                  echo '<tr><td colspan="7">No schedule for Thursday</td></tr>';
                }

                mysqli_close($conn);
                ?>


              </tbody>
            </table>

          </div>

          <div class="col-xl-2 text-center">
            <table class="table  mt-4">
              <thead class="table-dark">
                <tr>
                  <th>Friday</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Connect to your database (replace with actual database credentials)
                $conn = mysqli_connect("localhost", "root", "", "enrollment");

                // Check connection
                if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
                }

                // SQL query to fetch schedule for Monday
                $sql_friday = "SELECT subjects.subject_name FROM encodedstudentsubjects
              INNER JOIN student ON encodedstudentsubjects.student_id = student.student_id
              INNER JOIN schedules ON encodedstudentsubjects.schedule_id = schedules.id
              INNER JOIN subjects ON schedules.subject_id = subjects.subject_id
              WHERE student.student_id = $student_id AND schedules.day = 'Friday'";
                $result_friday = mysqli_query($conn, $sql_friday);

                if (mysqli_num_rows($result_friday) > 0) {
                  // Output data of each row
                  while ($row_friday = mysqli_fetch_assoc($result_friday)) {
                    echo '<tr>';
                    echo '<td>' . $row_friday["subject_name"] . '</td>';
                    // Add similar code for other days
                    echo '</tr>';
                  }
                } else {
                  // No schedule for Monday
                  echo '<tr><td colspan="7">No schedule for Friday</td></tr>';
                }

                mysqli_close($conn);
                ?>


              </tbody>
            </table>

          </div>

        </div>
        <!-- Week Days Schedule End-->

			</div>
		</div>

		<!-- js -->
		<script src="../vendors/scripts/core.js"></script>
		<script src="../vendors/scripts/script.min.js"></script>
		<script src="../vendors/scripts/process.js"></script>
		<script src="../vendors/scripts/layout-settings.js"></script>
		<script src="src/plugins/cropperjs/dist/cropper.js"></script>
		<script>
			window.addEventListener("DOMContentLoaded", function () {
				var image = document.getElementById("image");
				var cropBoxData;
				var canvasData;
				var cropper;

				$("#modal")
					.on("shown.bs.modal", function () {
						cropper = new Cropper(image, {
							autoCropArea: 0.5,
							dragMode: "move",
							aspectRatio: 3 / 3,
							restore: false,
							guides: false,
							center: false,
							highlight: false,
							cropBoxMovable: false,
							cropBoxResizable: false,
							toggleDragModeOnDblclick: false,
							ready: function () {
								cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
							},
						});
					})
					.on("hidden.bs.modal", function () {
						cropBoxData = cropper.getCropBoxData();
						canvasData = cropper.getCanvasData();
						cropper.destroy();
					});
			});
		</script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
