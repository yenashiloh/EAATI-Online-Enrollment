<?php

include 'config.php';

session_start();

$teacher_id = $_SESSION['teacher_id'];

if(!isset($teacher_id)){
   header('location:../login.php');
}

?>
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Dashboard</title>

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
			href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="../src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="title pb-20">
                <h2 class="h3 mb-0">Dashboard</h2>
            </div>

            <!-- Statistics Cards Row -->
            <div class="row pb-10">
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

                <!-- Verified Enrollee Card -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo "$verified_enrollee"; ?></div>
                                <div class="font-14 text-secondary weight-500">Total Verified</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#09cc06"> 
                                    <i class="fas fa-check-circle"></i> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Not Verified Enrollee Card -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo "$not_verified_enrollee"; ?></div>
                                <div class="font-14 text-secondary weight-500">Total Not Verified</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#ff5b5b">
                                    <i class="fas fa-times-circle"></i> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    // Query to count subjects
                    $query = "SELECT COUNT(*) AS subject_count FROM subjects";
                    $result = $link->query($query);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $subject_count = $row["subject_count"];
                    } else {
                        $subject_count = 0;
                    }
                ?>

                <!-- Total Subjects Card -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo "$subject_count"; ?></div>
                                <div class="font-14 text-secondary weight-500">Total Subjects</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#FFEB3B" >
                                    <i class="fas fa-book"></i> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    // Query to count sections
                    $query = "SELECT COUNT(*) AS section_count FROM sections";
                    $result = $link->query($query);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $section_count = $row["section_count"];
                    } else {
                        $section_count = 0;
                    }
                ?>

                <!-- Total Sections Card -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo "$section_count"; ?></div>
                                <div class="font-14 text-secondary weight-500">Total Sections</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#00eccf">
                                    <i class="fas fa-th-large"></i> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                    // Query to count rooms
                    $query = "SELECT COUNT(*) AS room_count FROM rooms";
                    $result = $link->query($query);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $room_count = $row["room_count"];
                    } else {
                        $room_count = 0;
                    }
                ?>

                <!-- Total Rooms Card -->
                <div class="col-xl-4 col-lg-3 col-md-6 mb-20">
                    <div class="card-box height-100-p widget-style3">
                        <div class="d-flex flex-wrap">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark"><?php echo "$room_count"; ?></div>
                                <div class="font-14 text-secondary weight-500">Total Rooms</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#FFEB3B">
                                    <i class="fas fa-door-open"></i> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                            
                    <div class="pd-20 d-flex justify-content-between align-items-center">
                        <h4 class="h4 mb-0">Students List</h4>
                        <a href="students.php" class="btn btn-primary">View All</a>
                    </div>
                        <?php
                            if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
                                echo "<div class='alert alert-success'>Record Deleted Successfully!</div>";
                            }
                        ?>
                    <div class="pb-20">
                    <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['verified']) && $_GET['verified'] == 1){
                            echo "<div class='alert alert-success'>Record verified successfully.</div>";
                        }
                        ?>
                    </div>
                    <?php
                    // Include config file
                    require_once "config1.php";

                    // Attempt select query execution
                    $sql = "SELECT * FROM student INNER JOIN users ON student.userId = users.id ";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="data-table table stripe hover nowrap">';
                                echo "<thead>";
                                    echo "<tr>";                                      
                                        echo "<th>Name</th>";
                                        echo "<th>Date of Birth</th>";
                                        echo "<th>Email</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['dob'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        
                                        echo "<td>";
                                        
                                        echo '<a href="view_record.php?id='.$row['student_id'].'" class="btn p-0 me-1" title="View Record"><span class="bi bi-eye-fill" style="font-size: 20px;"></span></a>';

                                        echo "</td>";
                                        
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>
                    </div>
        </div>
    </div>
		<script src="../vendors/scripts/core.js"></script>
		<script src="../vendors/scripts/script.min.js"></script>
		<script src="../vendors/scripts/process.js"></script>
		<script src="../src/plugins/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
		<script src="../src/plugins/datatables/js/dataTables.responsive.min.js"></script>
		<script src="../src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
		<!-- buttons for Export datatable -->
		<script src="../src/plugins/datatables/js/dataTables.buttons.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.print.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.html5.min.js"></script>
		<script src="../src/plugins/datatables/js/buttons.flash.min.js"></script>
		<script src="../src/plugins/datatables/js/pdfmake.min.js"></script>
		<script src="../src/plugins/datatables/js/vfs_fonts.js"></script>
		<!-- Datatable Setting js -->
		<script src="../vendors/scripts/datatable-setting.js"></script>
		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
	</body>
</html>
