<?php

include 'config.php';

session_start();

$student_id = $_SESSION['student_id'];

if(!isset($student_id)){
   header('location:login.php');
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
  <h1>View Grade</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item active">View Grade</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section profile">
  <div class="row">
    <div class="col-md-12 ">
    <div class="col-md-12 text-center">
    <a href="view_card.php?id=<?php echo $student_id; ?>" class="btn btn-primary btn-lg mr-3">View Grade</a>
    <button onclick="printViewCard()" class="btn btn-secondary btn-lg">Download Grade</button>
    </div>
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
  <script>
    function printViewCard() {
        // Get the student ID
        var student_id = <?php echo json_encode($student_id); ?>;
        
        // Open a new window with the student ID included as a query parameter
        var win = window.open('view_card.php?id=' + student_id, '_blank');
        
        // When the new window finishes loading, adjust page size and orientation, then print
        win.onload = function() {
            // Set the page size (for example, A4)
            win.document.body.style.width = "297mm";
            win.document.body.style.height = "210mm";
            
            
            // Print the content
            win.print();
        };
    }
</script>

</body>

</html>