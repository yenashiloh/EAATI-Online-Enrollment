<?php

include 'config.php';

session_start();

$parent_id = $_SESSION['parent_id'];

if(!isset($parent_id)){
   header('location:login.php');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - Parent</title>
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
              // Connect to your database (replace with actual database credentials)
              $conn = mysqli_connect("localhost", "root", "", "enrollment");

              // Check connection
              if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
              }

              // SQL query to fetch user profile
              $sql = "SELECT * FROM student WHERE userId = $parent_id"; // Assuming user_id 1 for demonstration

              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                  echo '<img src="' . $row["image_path"] . '" alt="Profile" class="rounded-circle" width="150" height="150">';
                  echo '<h2>' . $row["name"] . '</h2>';
                  echo '<h3>' . $row["grade_level"] . '</h3>';
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
              // Connect to your database (replace with actual database credentials)
              $conn = mysqli_connect("localhost", "root", "", "enrollment");

              // Check connection
              if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
              }

              // SQL query to fetch user profile
              $sql = "SELECT * FROM student WHERE userId = $parent_id"; // Assuming user_id 1 for demonstration

              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                  echo'<div class="row">';
                  echo'<div class="col-lg-3 col-md-4 label ">Date of Birth : </div>';
                  echo'<div class="col-lg-9 col-md-8">'. $row["dob"] .'</div>';
                  echo'</div>';

                  echo'<div class="row">';
                  echo'<div class="col-lg-3 col-md-4 label ">Place of Birth : </div>';
                  echo'<div class="col-lg-9 col-md-8">'. $row["pob"] .'</div>';
                  echo'</div>';

                  echo'<div class="row">';
                    echo'<div class="col-lg-3 col-md-4 label ">Email Address : </div>';
                    echo'<div class="col-lg-9 col-md-8">'. $row["email"] .'</div>';
                  echo'</div>';

                  echo'<div class="row">';
                  echo'<div class="col-lg-3 col-md-4 label ">Address : </div>';
                  echo'<div class="col-lg-9 col-md-8">'. $row["name"] .'</div>';
                  echo'</div>';
                }

                

              } else {
                echo "0 results";
              }

              mysqli_close($conn);
            ?>

            </div>

            <div class="tab-pane fade profile-edit" id="profile-edit">
            <h5 class="card-title">Parents/Guardian</h5>
            <?php
              // Connect to your database (replace with actual database credentials)
              $conn = mysqli_connect("localhost", "root", "", "enrollment");

              // Check connection
              if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
              }

              // SQL query to fetch user profile
              $sql = "SELECT * FROM student WHERE userId = $parent_id"; // Assuming user_id 1 for demonstration

              $result = mysqli_query($conn, $sql);

              if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while($row = mysqli_fetch_assoc($result)) {
                    
                    echo'<div class="row">';
                    echo '<label class="col-md-4 col-lg-3">Father : </label>';
                    echo'<div class="col-lg-9 col-md-8">'. $row["father_name"] .'</div>';
                    echo'</div>';

                    echo'<div class="row">';
                    echo '<label class="col-md-4 col-lg-3">Father Address : </label>';
                    echo'<div class="col-lg-9 col-md-8">'. $row["business_address_father"] .'</div>';
                    echo'</div>';

                    echo'<div class="row">';
                    echo '<label class="col-md-4 col-lg-3">Telephone Number : </label>';
                    echo'<div class="col-lg-9 col-md-8">'. $row["telephone_father"] .'</div>';
                    echo'</div>';

                    echo'<br>';

                    echo'<div class="row">';
                    echo '<label class="col-md-4 col-lg-3">Mother : </label>';
                    echo'<div class="col-lg-9 col-md-8">'. $row["mother_name"] .'</div>';
                    echo'</div>';

                    echo'<div class="row">';
                    echo '<label class="col-md-4 col-lg-3">Mother Address : </label>';
                    echo'<div class="col-lg-9 col-md-8">'. $row["business_address_mother"] .'</div>';
                    echo'</div>';

                    echo'<div class="row">';
                    echo '<label class="col-md-4 col-lg-3">Telephone Number : </label>';
                    echo'<div class="col-lg-9 col-md-8">'. $row["telephone_mother"] .'</div>';
                    echo'</div>';

                    echo'<br>';

                    echo'<div class="row">';
                    echo '<label class="col-md-4 col-lg-3">Guardian : </label>';
                    echo'<div class="col-lg-9 col-md-8">'. $row["guardian"] .'</div>';
                    echo'</div>';

                  }
                } else {
                  echo "0 results";
                }
  
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
            WHERE student.student_id = $parent_id AND schedules.day = 'Monday'";
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
            WHERE student.student_id = $parent_id AND schedules.day = 'Tuesday'";
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
            WHERE student.student_id = $parent_id AND schedules.day = 'Wednesday'";
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
            WHERE student.student_id = $parent_id AND schedules.day = 'Thursday'";
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
            WHERE student.student_id = $parent_id AND schedules.day = 'Friday'";
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