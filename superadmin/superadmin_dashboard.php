<?php

include 'config.php';

session_start();

$superadmin_id = $_SESSION['superadmin_id'];

if(!isset($superadmin_id)){
   header('location:login.php');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - Super Admin</title>
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
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

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
                  <h5 class="card-title">Teachers</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/teacher.png" alt="Teacher Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                      include 'config1.php'; // Include your database configuration file

                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS teacher_count FROM users WHERE role = 'teacher'";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $teacher_count = $row["teacher_count"];
                      } else {
                          $teacher_count = 0;
                      }

                      
                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$teacher_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Students</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/student.png" alt="Student Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                    
                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS student_count FROM users WHERE role = 'student'";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $student_count = $row["student_count"];
                      } else {
                          $student_count = 0;
                      }

                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$student_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Parents</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/parents.png" alt="Parent Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                    
                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS parent_count FROM users WHERE role = 'parent'";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $parent_count = $row["parent_count"];
                      } else {
                          $parent_count = 0;
                      }

                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$parent_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Registrar</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/register.png" alt="Registrar Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                    
                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS registrar_count FROM users WHERE role = 'registrar'";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $registrar_count = $row["registrar_count"];
                      } else {
                          $registrar_count = 0;
                      }

                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$registrar_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

        </div>

        <div class="row justify-content-center">

        <!-- Left side columns -->
          <div class="row justify-content-center">

            <!-- Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Accounting</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/accountant.png" alt="Accounting Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                    
                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS accounting_count FROM users WHERE role = 'accounting'";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $accounting_count = $row["accounting_count"];
                      } else {
                          $accounting_count = 0;
                      }

                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$accounting_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->
            <div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">Superadmin</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <img src="../images/administrator.png" alt="Student Count" style="margin-left: 40px; width: 100px; height: 100px;">
                    </div>
                    <?php
                    
                      // Check connection
                      if ($link->connect_error) {
                          die("Connection failed: " . $link->connect_error);
                      }

                      // Query to count teachers
                      $query = "SELECT COUNT(*) AS superadmin_count FROM users WHERE role = 'superadmin'";
                      $result = $link->query($query);

                      if ($result && $result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $superadmin_count = $row["superadmin_count"];
                      } else {
                          $superadmin_count = 0;
                      }

                    ?>
                    <div class="ps-3" style="margin-left: 50px;">
                      <h1><?php echo "$superadmin_count"; ?></h1>
                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->
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