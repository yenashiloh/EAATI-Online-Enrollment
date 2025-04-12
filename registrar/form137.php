<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if(!isset($registrar_id)){
   header('location:../login.php');
   exit; 
}

?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>	Form 137</title>

       <?php
         include 'link.php';
       ?>

	</head>
	<body class="sidebar-light">
    <?php
        include 'header.php';
        include 'sidebar.php';

        // Fetch user details for editing
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM schedules WHERE id = :id";
            $query = $conn->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $student = $query->fetch(PDO::FETCH_ASSOC);
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
                                    <h4>Form 138</h4>
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="teacher_dashboard.php">Menu</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Form 138
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                        <?php
                        if(isset($_GET['verified']) && $_GET['verified'] == 1){
                            echo "<div class='alert alert-success'>Record verified successfully.</div>";
                        }
                        ?>
                        <div class="pb-20">
                            <?php
                            require_once "config1.php";

                            $sql = "SELECT * FROM student INNER JOIN users ON student.userId = users.id WHERE student.isVerified = 1";
                            if ($result = mysqli_query($link, $sql)) {
                                if (mysqli_num_rows($result) > 0) {
                                    echo '<table class="data-table table stripe hover nowrap">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>No.</th>"; 
                                    echo "<th>Name</th>";
                                    echo "<th>Date of Birth</th>";
                                    echo "<th>Email</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";

                                    $counter = 1; 
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $counter . "</td>"; 
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['dob'] . "</td>";
                                        echo "<td>" . $row['email'] . "</td>";
                                        echo "<td>";
                                        echo '<a href="view_form_137.php?id=' . $row['student_id'] . '" class="btno" title="View Record" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <span class="bi bi-eye-fill" style="font-size:20px;"></span>
                                            </a>';
                                        echo '  ';
                                        echo "</td>";
                                        echo "</tr>";

                                        $counter++;
                                    }

                                    echo "</tbody>";
                                    echo "</table>";
                                    mysqli_free_result($result);
                                } else {
                                    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                }
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                            mysqli_close($link);
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>

		<?php
         include 'footer.php';
       ?>

	</body>
</html>
