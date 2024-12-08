<?php
session_start(); // Start the session at the very beginning

include 'config.php'; // Include your configuration after session_start()

$student_id = $_SESSION['student_id'];

if (!isset($student_id)) {
  header('location:login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - Student</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

  <?php
  include 'header.php';
  include 'sidebar.php';
  ?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Student Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Student Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-4">

          <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
              <?php
              $conn = mysqli_connect("localhost", "root", "", "enrollment");

              if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
              }

              $sql = "SELECT * FROM student WHERE userId = $student_id"; 

              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                  echo '<img src="' . $row["image_path"] . '" alt="Profile" class="rounded-circle" width="150" height="150">';
                  echo '<h2>' . $row["name"] . '</h2>';
                  echo '<br>';
                  echo '<h3> Grade ' . $row["grade_level_id"] . '</h3>';
                }
              } else {
                echo "0 results";
              }

              mysqli_close($conn);
              ?>
            </div>
          </div>

        </div>

        <div class="col-xl-8">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Profile</button>
                </li>
                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Parent/Guardian</button>
                </li>
              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                  <h5 class="card-title">Profile Details</h5>

                  <?php
                  $conn = mysqli_connect("localhost", "root", "", "enrollment");

                  if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                  }

                  $sql = "SELECT * FROM student WHERE userId = $student_id"; 

                  $result = mysqli_query($conn, $sql);

                  if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo '<div class="row">';
                      echo '<div class="col-lg-3 col-md-4 label ">Date of Birth : </div>';
                      echo '<div class="col-lg-9 col-md-8">' . $row["dob"] . '</div>';
                      echo '</div>';

                      echo '<div class="row">';
                      echo '<div class="col-lg-3 col-md-4 label ">Place of Birth : </div>';
                      echo '<div class="col-lg-9 col-md-8">' . $row["pob"] . '</div>';
                      echo '</div>';

                      echo '<div class="row">';
                      echo '<div class="col-lg-3 col-md-4 label ">Email Address : </div>';
                      echo '<div class="col-lg-9 col-md-8">' . $row["email"] . '</div>';
                      echo '</div>';

                      echo '<div class="row">';
                      echo '<div class="col-lg-3 col-md-4 label ">Address : </div>';
                      echo '<div class="col-lg-9 col-md-8">' . $row["student_house_number"] . ' ' . $row["student_street"] . ', ' . $row["student_barangay"] . ', ' . $row["student_municipality"] . '</div>';
                      echo '</div>';

                      echo '<div class="row">';
                      echo '<div class="col-lg-3 col-md-4 label ">Gender : </div>';
                      echo '<div class="col-lg-9 col-md-8">' . $row["gender"] . '</div>';
                      echo '</div>';

                      echo '<div class="row">';
                      echo '<div class="col-lg-3 col-md-4 label ">Guardian : </div>';
                      echo '<div class="col-lg-9 col-md-8">' . $row["guardian"] . '</div>';
                      echo '</div>';
                    }
                  } else {
                    echo "0 results";
                  }

                  mysqli_close($conn);
                  ?>

                </div>

                <div class="tab-pane fade profile-edit" id="profile-edit">
                 
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
                          WHERE s.student_id = ?
                         ";

                      $stmt = mysqli_prepare($conn, $sql);
                      if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "i", $student_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                          $row = mysqli_fetch_assoc($result);
                          ?>
                          <!-- Father Information Section -->
                          <h6 class=" card-title">Father Information</h6>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Father Name: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["father_name"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">House Number: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["houseNo_father"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Street: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["street_father"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Barangay: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["barangay_father"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Municipality: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["municipality_father"] ?? ''); ?></div>
                          </div>
                          <div class="row mb-4">
                            <label class="col-md-4 col-lg-3">Contact Number: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["telephone_father"] ?? ''); ?></div>
                          </div>

                          <!-- Mother Information Section -->
                          <h6 class=" card-title">Mother Information</h6>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Mother Name: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["mother_name"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">House Number: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["houseNo_mother"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Street: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["street_mother"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Barangay: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["barangay_mother"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Municipality: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["municipality_mother"] ?? ''); ?></div>
                          </div>
                          <div class="row">
                            <label class="col-md-4 col-lg-3">Contact Number: </label>
                            <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($row["telephone_mother"] ?? ''); ?></div>
                          </div>
                  <?php
                        } else {
                          echo "No parent information found for student ID: " . $student_id;
                        }
                        mysqli_stmt_close($stmt);
                      } else {
                        echo "Error preparing the statement: " . mysqli_error($conn);
                      }
                    } else {
                      echo "No student found for user ID: " . $userId;
                    }
                    mysqli_stmt_close($stmt_student);
                  } else {
                    echo "Error preparing the student query: " . mysqli_error($conn);
                  }

                  // Close database connection
                  mysqli_close($conn);
                  ?>
                </div>

              </div><!-- End Bordered Tabs -->
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-xl-2 text-center">
          <table class="table">
            <thead class="table-dark">
              <tr>
                <th>Monday</th>
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
              $sql_monday = "SELECT subjects.subject_name FROM encodedstudentsubjects
            INNER JOIN student ON encodedstudentsubjects.student_id = student.student_id
            INNER JOIN schedules ON encodedstudentsubjects.schedule_id = schedules.id
            INNER JOIN subjects ON schedules.subject_id = subjects.subject_id
            WHERE student.student_id = $student_id AND schedules.day = 'Monday'";
              $result_monday = mysqli_query($conn, $sql_monday);

              if (mysqli_num_rows($result_monday) > 0) {
                // Output data of each row
                while ($row_monday = mysqli_fetch_assoc($result_monday)) {
                  echo '<tr>';
                  echo '<td>' . $row_monday["subject_name"] . '</td>';
                  // Add similar code for other days
                  echo '</tr>';
                }
              } else {
                // No schedule for Monday
                echo '<tr><td colspan="7">No schedule for Monday</td></tr>';
              }

              mysqli_close($conn);
              ?>


            </tbody>
          </table>

        </div>
        <div class="col-xl-2 text-center">
          <table class="table">
            <thead class="table-dark">
              <tr>
                <th>Tuesday</th>
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
          <table class="table">
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
          <table class="table">
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
          <table class="table">
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
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php
  include 'footer.php';
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>