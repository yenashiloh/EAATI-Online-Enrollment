<?php
include 'config.php';

session_start();
error_reporting(E_ALL);

$teacher_id = $_SESSION['teacher_id'];

if(!isset($teacher_id)){
    header('location:../login.php');
    exit;
}

$error = $msg = "";

$student = $father = $mother = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT s.*, g.gradelevel_name FROM student s
            LEFT JOIN gradelevel g ON s.grade_level_id = g.gradelevel_id
            WHERE s.student_id = :id";
    $query = $conn->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $student = $query->fetch(PDO::FETCH_ASSOC);

    $sql_father = "SELECT * FROM father_information WHERE student_id = :id";
    $query_father = $conn->prepare($sql_father);
    $query_father->bindParam(':id', $id, PDO::PARAM_INT);
    $query_father->execute();
    $father = $query_father->fetch(PDO::FETCH_ASSOC);

    $sql_mother = "SELECT * FROM mother_information WHERE student_id = :id";
    $query_mother = $conn->prepare($sql_mother);
    $query_mother->bindParam(':id', $id, PDO::PARAM_INT);
    $query_mother->execute();
    $mother = $query_mother->fetch(PDO::FETCH_ASSOC);
}

$sql_grades = "SELECT gradelevel_id, gradelevel_name FROM gradelevel";
$query_grades = $conn->prepare($sql_grades);
$query_grades->execute();
$grades = $query_grades->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Students</title>

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
									<h4>Students</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="registrar_dashboard.php">Menu</a>
										</li>
                                        <li class="breadcrumb-item">
											<a href="students.php">Students</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											View Student
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                        
                        <div class="pb-20">
                        <?php if ($error): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                    </div>
                                <?php elseif ($msg): ?>
                                    <div class="alert alert-success" role="alert">
                                        <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                    </div>
                                <?php endif; ?>

                                <form method="post" action="verify_another.php">
                                    <?php if (isset($id)): ?>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <?php endif; ?>

                                    <div class="row">
                                        <div class="col-md-9" style="margin-top: 120px;">
                                            <label for="sname" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="sname" name="sname" value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <img id="preview" src="<?php echo htmlspecialchars($student['image_path'] ?? '../images/profile.jpg'); ?>" alt="Profile Picture" class="mx-auto d-block" style="width: 200px; height: 200px; cursor: pointer;" onclick="triggerFileUpload()">
                                            <input type="file" accept="image/*" class="form-control" id="image" name="image" onchange="previewImage(event)" style="display: none;" disabled>
                                            <label for="image" class="form-label">Profile</label>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $student['dob']; ?>" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="pob" class="form-label">Place of Birth</label>
                                            <input type="text" class="form-control" id="pob" name="pob" value="<?php echo $student['pob']; ?>" disabled>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="pob" class="form-label">Age</label>
                                            <input type="text" class="form-control" id="age" name="age" readonly value="<?php echo $student['age']; ?> years old" disabled>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="pob" class="form-label">Gender</label>
                                            <input type="text" class="form-control" id="gender" name="gender" readonly value="<?php echo $student['gender']; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="student_house_number" class="form-label">House Number</label>
                                            <input type="text" class="form-control" id="student_house_number" name="student_house_number	" value="<?php echo htmlspecialchars($student['student_house_number'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="student_street" class="form-label">Street</label>
                                            <input type="text" class="form-control" id="student_street" name="student_street" value="<?php echo htmlspecialchars($student['student_street'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="student_barangay" class="form-label">Barangay</label>
                                            <input type="text" class="form-control" id="student_barangay" name="student_barangay" value="<?php echo htmlspecialchars($student['student_barangay'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="student_municipality" class="form-label">Municipality</label>
                                            <input type="text" class="form-control" id="student_municipality" name="student_municipality" value="<?php echo htmlspecialchars($student['student_municipality'] ?? ''); ?>" disabled>
                                        </div>

                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="previous_school" class="form-label">Previous School Attended</label>
                                            <input type="text" class="form-control" id="previous_school" name="previous_school" value="<?php echo $student['previous_school']; ?>" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_address" class="form-label">Address of School</label>
                                            <input type="text" class="form-control" id="school_address" name="school_address" value="<?php echo $student['school_address']; ?>" disabled>
                                        </div>
                                    </div>

                                    <hr size=8 noshade>
                                    <h6 class=" card-title mt-4">Father Information</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="father_name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="father_name" name="father_name"
                                                value="<?php echo htmlspecialchars($father['father_name'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="telephone_father" class="form-label">Telephone</label>
                                            <input type="tel" class="form-control" id="telephone_father" name="telephone_father"
                                                value="<?php echo htmlspecialchars($father['telephone_father'] ?? ''); ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label for="houseNo_father" class="form-label">House No.</label>
                                            <input type="text" class="form-control" id="houseNo_father" name="houseNo_father"
                                                value="<?php echo htmlspecialchars($father['houseNo_father'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="street_father" class="form-label">Street</label>
                                            <input type="text" class="form-control" id="street_father" name="street_father"
                                                value="<?php echo htmlspecialchars($father['street_father'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="barangay_father" class="form-label">Barangay</label>
                                            <input type="text" class="form-control" id="barangay_father" name="barangay_father"
                                                value="<?php echo htmlspecialchars($father['barangay_father'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="municipality_father" class="form-label">Municipality</label>
                                            <input type="text" class="form-control" id="municipality_father" name="municipality_father"
                                                value="<?php echo htmlspecialchars($father['municipality_father'] ?? ''); ?>" disabled>
                                        </div>
                                    </div>

                                    <hr size=8 noshade>
                                    <h6 class=" card-title mt-4">Mother Information</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="mother_name" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="mother_name" name="mother_name"
                                                value="<?php echo htmlspecialchars($mother['mother_name'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="telephone_mother" class="form-label">Telephone</label>
                                            <input type="tel" class="form-control" id="telephone_mother" name="telephone_mother"
                                                value="<?php echo htmlspecialchars($mother['telephone_mother'] ?? ''); ?>" disabled>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="houseNo_mother" class="form-label">House No.</label>
                                            <input type="text" class="form-control" id="houseNo_mother" name="houseNo_mother"
                                                value="<?php echo htmlspecialchars($mother['houseNo_mother'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="street_mother" class="form-label">Street</label>
                                            <input type="text" class="form-control" id="street_mother" name="street_mother"
                                                value="<?php echo htmlspecialchars($mother['street_mother'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="barangay_mother" class="form-label">Barangay</label>
                                            <input type="text" class="form-control" id="barangay_mother" name="barangay_mother"
                                                value="<?php echo htmlspecialchars($mother['barangay_mother'] ?? ''); ?>" disabled>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="municipality_mother" class="form-label">Municipality</label>
                                            <input type="text" class="form-control" id="municipality_mother" name="municipality_mother"
                                                value="<?php echo htmlspecialchars($mother['municipality_mother'] ?? ''); ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="guardian" class="form-label">Guardian (for absent parent/s)</label>
                                            <input type="text" class="form-control" id="guardian" name="guardian" value="<?php echo $student['guardian']; ?>" disabled>
                                        </div>
                                    </div>

                                    <hr size=8 noshade>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="grade_level" class="form-label">Grade Level</label>
                                            <select class="custom-select col-12" id="grade_level" name="grade_level" disabled style="color:black;">
                                                <?php foreach ($grades as $grade): ?>
                                                    <option value="<?php echo $grade['gradelevel_id']; ?>" <?php if ($student['grade_level_id'] == $grade['gradelevel_id']) echo 'selected'; ?>>
                                                        <?php echo $grade['gradelevel_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                            <div class="col-md-6" style="margin-top: 20px;">
                                                <label for="requirements" class="form-label">Uploaded Requirements:</label>
                                                <?php
                                                $sql = "SELECT requirements FROM student WHERE student_id = :id";
                                                $query = $conn->prepare($sql);
                                                $query->bindParam(':id', $id, PDO::PARAM_INT);
                                                $query->execute();
                                                $filesJson = $query->fetchColumn();
                                                $files = json_decode($filesJson, true);

                                                if ($files) {
                                                    echo "<ul>";
                                                    foreach ($files as $file) {
                                                        echo "<li><a href='" . $file . "' target='_blank' style='color: blue; text-decoration: underline;'>" . basename($file) . "</a></li>";

                                                    }
                                                    echo "</ul>";
                                                }
                                                ?>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

		<?php
            include 'footer.php';
        ?>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
