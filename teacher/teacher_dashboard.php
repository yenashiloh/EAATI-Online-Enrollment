<?php

include 'config.php';

session_start();

$teacher_id = $_SESSION['teacher_id'];

if(!isset($teacher_id)){
   header('location:login.php');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - Teacher</title>
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
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Enrollment</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/inscription.png" alt="Enrollment Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                      include 'config1.php'; // Include your database configuration file

                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS verified_enrollee FROM student WHERE isVerified = 1";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $verified_enrollee = $row["verified_enrollee"];
                      } else {
                          $verified_enrollee = 0;
                      }

                      $query_not_verified = "SELECT COUNT(*) AS not_verified_enrollee FROM student WHERE isVerified = 0";
                      $result_not_verified = $link->query($query_not_verified);

                      if ($result_not_verified && $result_not_verified->num_rows > 0) {
                          $row_not_verified = $result_not_verified->fetch_assoc();
                          $not_verified_enrollee = $row_not_verified["not_verified_enrollee"];
                      } else {
                          $not_verified_enrollee = 0;
                      }
                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h5>Verified: <?php echo "$verified_enrollee"; ?></h5>
                      <h5>Not yet Verified: <?php echo "$not_verified_enrollee"; ?></h5>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Subjects</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/books.png" alt="Subject Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                    
                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS subject_count FROM subjects";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $subject_count = $row["subject_count"];
                      } else {
                          $subject_count = 0;
                      }

                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$subject_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Sections</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/chapter.png" alt="Sections Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                    
                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS section_count FROM sections";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $section_count = $row["section_count"];
                      } else {
                          $section_count = 0;
                      }

                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$section_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Rooms</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/classroom.png" alt="Classroom Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                    
                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS room_count FROM rooms";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $room_count = $row["room_count"];
                      } else {
                          $room_count = 0;
                      }

                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$room_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

        </div>
</section>
</div>
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