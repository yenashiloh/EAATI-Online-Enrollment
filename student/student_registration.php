<?php
include 'config.php';

session_start(); 
error_reporting(E_ALL);

if (!isset($_SESSION['student_id'])) {
    header('location:login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$error = "";
$msg = "";

// Get user information first
$query = $conn->prepare("SELECT * FROM users WHERE id = :student_id");
$query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC); 

// Combine first and last name
$full_name = '';
if (isset($user['first_name'])) {
    $full_name = $user['first_name'];
    if (isset($user['last_name'])) {
        $full_name .= ' ' . $user['last_name'];
    }
}

// Get email from users table
$student_email = isset($user['email']) ? $user['email'] : '';

// UPDATE instead of INSERT for existing student
if (isset($_POST['add_registration'])) {
    // Get student information from POST
    $grade_level_id = $_POST['grade_level'];
    $dob = $_POST['dob'];
    $pob = $_POST['pob'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $student_house_number = $_POST['student_house_number'];
    $student_street = $_POST['student_street'];
    $student_barangay = $_POST['student_barangay'];
    $student_municipality = $_POST['student_municipality'];
    $guardian = $_POST['guardian'];
    $previous_school = $_POST['previous_school'];
    $school_address = $_POST['school_address'];
    $grade_level = $_POST['grade_level'];

    // Get father information from POST
    $father_name = $_POST['father_name'];
    $telephone_father = $_POST['telephone_father'];
    $houseNo_father = $_POST['houseNo_father'];
    $street_father = $_POST['street_father'];
    $barangay_father = $_POST['barangay_father'];
    $municipality_father = $_POST['municipality_father'];

    // Get mother information from POST
    $mother_name = $_POST['mother_name'];
    $telephone_mother = $_POST['telephone_mother'];
    $houseNo_mother = $_POST['houseNo_mother'];
    $street_mother = $_POST['street_mother'];
    $barangay_mother = $_POST['barangay_mother'];
    $municipality_mother = $_POST['municipality_mother'];

    // Handle requirements upload
    $uploaded_files = [];
    if (isset($_FILES['requirements']) && is_array($_FILES['requirements']['name'])) {
        $file_count = count($_FILES['requirements']['name']);
        $allowed_extensions = array('pdf');

        for ($i = 0; $i < $file_count; $i++) {
            $file_name = $_FILES['requirements']['name'][$i];
            $file_tmp = $_FILES['requirements']['tmp_name'][$i];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $destination = '../uploads/' . $file_name;

            if (in_array($file_extension, $allowed_extensions)) {
                if (move_uploaded_file($file_tmp, $destination)) {
                    $uploaded_files[] = $destination;
                } else {
                    $error = "Failed to move uploaded file: $file_name";
                    break;
                }
            } else {
                $error = "File type not allowed for $file_name. Only PDF files are allowed.";
                break;
            }
        }
    }

    if (empty($error)) {
        try {
            $conn->beginTransaction();

            // Check if student record already exists
            $check_sql = "SELECT * FROM student WHERE userId = :student_id";
            $check_query = $conn->prepare($check_sql);
            $check_query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $check_query->execute();
            $student_data = $check_query->fetch(PDO::FETCH_ASSOC);
            $student_exists = $student_data ? true : false;
            
            // Get the student_id from the student table (not the id column)
            $student_record_id = $student_exists && isset($student_data['student_id']) ? $student_data['student_id'] : 0;

            if ($student_exists) {
                // UPDATE existing student record
                $sql = "UPDATE student SET 
                    name = :full_name, 
                    dob = :dob, 
                    pob = :pob, 
                    age = :age, 
                    gender = :gender, 
                    student_house_number = :student_house_number, 
                    student_street = :student_street, 
                    student_barangay = :student_barangay, 
                    student_municipality = :student_municipality, 
                    guardian = :guardian, 
                    previous_school = :previous_school, 
                    school_address = :school_address, 
                    grade_level_id = :grade_level_id";
                
                // Only update requirements if new files were uploaded
                if (!empty($uploaded_files)) {
                    $sql .= ", requirements = :requirements";
                }
                
                $sql .= " WHERE userId = :student_id";

                $query = $conn->prepare($sql);
                $query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $query->bindParam(':full_name', $full_name, PDO::PARAM_STR);
                $query->bindParam(':dob', $dob, PDO::PARAM_STR);
                $query->bindParam(':pob', $pob, PDO::PARAM_STR);
                $query->bindParam(':age', $age, PDO::PARAM_INT);
                $query->bindParam(':gender', $gender, PDO::PARAM_STR);
                $query->bindParam(':student_house_number', $student_house_number, PDO::PARAM_STR);
                $query->bindParam(':student_street', $student_street, PDO::PARAM_STR);
                $query->bindParam(':student_barangay', $student_barangay, PDO::PARAM_STR);
                $query->bindParam(':student_municipality', $student_municipality, PDO::PARAM_STR);
                $query->bindParam(':guardian', $guardian, PDO::PARAM_STR);
                $query->bindParam(':previous_school', $previous_school, PDO::PARAM_STR);
                $query->bindParam(':school_address', $school_address, PDO::PARAM_STR);
                $query->bindParam(':grade_level_id', $grade_level_id, PDO::PARAM_INT);
                
                if (!empty($uploaded_files)) {
                    $query->bindParam(':requirements', json_encode($uploaded_files), PDO::PARAM_STR);
                }
                
                $query->execute();
            } else {
                // Original INSERT code for new student
                $sql = "INSERT INTO student (userId, name, dob, pob, age, gender, student_house_number, student_street, 
                        student_barangay, student_municipality, guardian, previous_school, school_address, grade_level_id, 
                        requirements, isVerified) 
                        VALUES (:student_id, :full_name, :dob, :pob, :age, :gender, :student_house_number, :student_street,
                        :student_barangay, :student_municipality, :guardian, :previous_school, :school_address, 
                        :grade_level_id, :requirements, 0)";

                $query = $conn->prepare($sql);
                $query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $query->bindParam(':full_name', $full_name, PDO::PARAM_STR);
                $query->bindParam(':dob', $dob, PDO::PARAM_STR);
                $query->bindParam(':pob', $pob, PDO::PARAM_STR);
                $query->bindParam(':age', $age, PDO::PARAM_INT);
                $query->bindParam(':gender', $gender, PDO::PARAM_STR);
                $query->bindParam(':student_house_number', $student_house_number, PDO::PARAM_STR);
                $query->bindParam(':student_street', $student_street, PDO::PARAM_STR);
                $query->bindParam(':student_barangay', $student_barangay, PDO::PARAM_STR);
                $query->bindParam(':student_municipality', $student_municipality, PDO::PARAM_STR);
                $query->bindParam(':guardian', $guardian, PDO::PARAM_STR);
                $query->bindParam(':previous_school', $previous_school, PDO::PARAM_STR);
                $query->bindParam(':school_address', $school_address, PDO::PARAM_STR);
                $query->bindParam(':grade_level_id', $grade_level_id, PDO::PARAM_INT);
                $query->bindParam(':requirements', json_encode($uploaded_files), PDO::PARAM_STR);
                $query->execute();
                
                // Get the newly inserted student_id - need to query for it since it's likely auto-increment
                $get_student_id_sql = "SELECT student_id FROM student WHERE userId = :user_id ORDER BY student_id DESC LIMIT 1";
                $get_student_id_query = $conn->prepare($get_student_id_sql);
                $get_student_id_query->bindParam(':user_id', $student_id, PDO::PARAM_INT);
                $get_student_id_query->execute();
                $new_student_data = $get_student_id_query->fetch(PDO::FETCH_ASSOC);
                if ($new_student_data && isset($new_student_data['student_id'])) {
                    $student_record_id = $new_student_data['student_id'];
                } else {
                    throw new PDOException("Failed to retrieve new student_id");
                }
            }
            
            // Only proceed if we have a valid student_id
            if ($student_record_id > 0) {
                // First delete any existing father information to avoid duplicates
                $delete_father_sql = "DELETE FROM father_information WHERE student_id = :student_id";
                $delete_father_query = $conn->prepare($delete_father_sql);
                $delete_father_query->bindParam(':student_id', $student_record_id, PDO::PARAM_INT);
                $delete_father_query->execute();
                
                // Insert father information
                $father_sql = "INSERT INTO father_information (student_id, userId, father_name, telephone_father, houseNo_father, 
                              street_father, barangay_father, municipality_father) 
                              VALUES (:student_id, :user_id, :father_name, :telephone_father, :houseNo_father, :street_father, 
                              :barangay_father, :municipality_father)";

                $father_query = $conn->prepare($father_sql);
                $father_query->bindParam(':student_id', $student_record_id, PDO::PARAM_INT);
                $father_query->bindParam(':user_id', $student_id, PDO::PARAM_INT);
                $father_query->bindParam(':father_name', $father_name, PDO::PARAM_STR);
                $father_query->bindParam(':telephone_father', $telephone_father, PDO::PARAM_STR);
                $father_query->bindParam(':houseNo_father', $houseNo_father, PDO::PARAM_STR);
                $father_query->bindParam(':street_father', $street_father, PDO::PARAM_STR);
                $father_query->bindParam(':barangay_father', $barangay_father, PDO::PARAM_STR);
                $father_query->bindParam(':municipality_father', $municipality_father, PDO::PARAM_STR);
                $father_query->execute();

                // First delete any existing mother information to avoid duplicates
                $delete_mother_sql = "DELETE FROM mother_information WHERE student_id = :student_id";
                $delete_mother_query = $conn->prepare($delete_mother_sql);
                $delete_mother_query->bindParam(':student_id', $student_record_id, PDO::PARAM_INT);
                $delete_mother_query->execute();
                
                // Insert mother information
                $mother_sql = "INSERT INTO mother_information (student_id, userId, mother_name, telephone_mother, houseNo_mother, 
                              street_mother, barangay_mother, municipality_mother) 
                              VALUES (:student_id, :user_id, :mother_name, :telephone_mother, :houseNo_mother, :street_mother, 
                              :barangay_mother, :municipality_mother)";

                $mother_query = $conn->prepare($mother_sql);
                $mother_query->bindParam(':student_id', $student_record_id, PDO::PARAM_INT);
                $mother_query->bindParam(':user_id', $student_id, PDO::PARAM_INT);
                $mother_query->bindParam(':mother_name', $mother_name, PDO::PARAM_STR);
                $mother_query->bindParam(':telephone_mother', $telephone_mother, PDO::PARAM_STR);
                $mother_query->bindParam(':houseNo_mother', $houseNo_mother, PDO::PARAM_STR);
                $mother_query->bindParam(':street_mother', $street_mother, PDO::PARAM_STR);
                $mother_query->bindParam(':barangay_mother', $barangay_mother, PDO::PARAM_STR);
                $mother_query->bindParam(':municipality_mother', $municipality_mother, PDO::PARAM_STR);
                $mother_query->execute();

                $conn->commit();
                $msg = "Registration successfully " . ($student_exists ? "updated" : "inserted");
            } else {
                throw new PDOException("Invalid student_id");
            }
        } catch (PDOException $e) {
            $conn->rollBack();
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Student Registration</title>

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
									<h4>Student Registration</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="student_dashboard.php">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Student Registration
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>

                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <?php 
                        try {
                            $conn = new PDO("mysql:host=localhost;dbname=enrollment", "root", "");
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        } catch(PDOException $e) {
                            die("Connection failed: " . $e->getMessage());
                        }

                        if ($error) { ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                            </div>
                        <?php } 

                        try {
                            $query = $conn->prepare("SELECT * FROM student WHERE userId = :student_id");
                            $query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                            $query->execute();
                            $result = $query->fetch(PDO::FETCH_ASSOC);
                            
                            if ($result && !empty($result['grade_level_id'])) { ?>
                                <div class="alert alert-info text-center" style="font-size: 22px;">
                                    Thank you for your submission. We have received your registration.<br>
                                    Please be patient as our registrar verifies your information.<br>
                                    Once your registration is verified, you will receive a confirmation email.<br>
                                    If you have any further questions or concerns, please don't hesitate to reach out to us.
                                </div>
                            <?php }
                        } catch(PDOException $e) {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo '<strong>Database Error:</strong> ' . htmlentities($e->getMessage());
                            echo '</div>';
                        }
                        ?>


                                <!-- Grade Level selection -->

                                <div class="form-group row justify-content-center align-items-center" id="grade_level_container">
                                    <label class="col-auto col-form-label text-center">Grade Level</label>
                                        <div class="col-4">
                                            <select class="custom-select" id="grade_level" name="grade_level">
                                                <option selected="">Select Grade Level</option>
                                                <?php
                                                    include "config1.php";
                                                    $sql = "SELECT * FROM gradelevel";
                                                    $result = mysqli_query($link, $sql);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        echo "<option value='" . $row['gradelevel_id'] . "'>" . $row['gradelevel_name'] . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                
                                <form method="post" name="add_registration" onSubmit="return valid();" enctype="multipart/form-data" id="registrationForm" style="display: none;">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="sname" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="sname" name="sname" 
                                            value="<?php echo htmlentities($full_name); ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" id="dob" name="dob" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label for="age" class="form-label">Age</label>
                                            <input type="text" class="form-control" id="age" name="age" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="pob" class="form-label">Place of Birth</label>
                                            <input type="text" class="form-control" id="pob" name="pob" required>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select class="form-control" id="gender" name="gender" required>
                                                <option value="">-- Select Gender --</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                            value="<?php echo htmlentities($student_email); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="student_house_number" class="form-label">House Number</label>
                                            <input type="text" class="form-control" id="student_house_number" name="student_house_number" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="student_street" class="form-label">Street</label>
                                            <input type="text" class="form-control" id="student_street" name="student_street" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="student_barangay" class="form-label">Barangay</label>
                                            <input type="text" class="form-control" id="student_barangay" name="student_barangay" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="student_municipality" class="form-label">Municipality</label>
                                            <input type="text" class="form-control" id="student_municipality" name="student_municipality" required>
                                        </div>
                                    </div>

                                    <!-- Father Information Section -->
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="address" class="form-label fw-bold mt-4 mb-3">FATHER INFORMATION</label>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="father_name" class="form-label">Name of Father</label>
                                            <input type="text" class="form-control" id="father_name" name="father_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="telephone_father" class="form-label">Contact Number(Father)</label>
                                            <input type="number" class="form-control" id="telephone_father" name="telephone_father" required>
                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="houseNo_father" class="form-label">House Number</label>
                                            <input type="text" class="form-control" id="houseNo_father" name="houseNo_father" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="street_father" class="form-label">Street</label>
                                            <input type="text" class="form-control" id="street_father" name="street_father" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="barangay_father" class="form-label">Barangay</label>
                                            <input type="text" class="form-control" id="barangay_father" name="barangay_father" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="municipality_father" class="form-label">Municipality</label>
                                            <input type="text" class="form-control" id="municipality_father" name="municipality_father" required>
                                        </div>
                                    </div>

                                    <!-- Mother Information Section -->
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="address" class="form-label fw-bold mt-4 mb-3">MOTHER INFORMATION</label>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="mother_name" class="form-label">Name of Mother</label>
                                            <input type="text" class="form-control" id="mother_name" name="mother_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="telephone_mother" class="form-label">Telephone (Mother)</label>
                                            <input type="number" class="form-control" id="telephone_mother" name="telephone_mother" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="houseNo_mother" class="form-label">House Number</label>
                                            <input type="text" class="form-control" id="houseNo_mother" name="houseNo_mother" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="street_mother" class="form-label">Street</label>
                                            <input type="text" class="form-control" id="street_mother" name="street_mother" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="barangay_mother" class="form-label">Barangay</label>
                                            <input type="text" class="form-control" id="barangay_mother" name="barangay_mother" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="municipality_mother" class="form-label">Municipality</label>
                                            <input type="text" class="form-control" id="municipality_mother" name="municipality_mother" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="guardian" class="form-label">Guardian (for absent parent/s)</label>
                                            <input type="text" class="form-control" id="guardian" name="guardian" required>
                                        </div>
                                    </div>

                                    <!-- School Information Section -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="previous_school" class="form-label">Previous School Attended</label>
                                            <input type="text" class="form-control" id="previous_school" name="previous_school" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="school_address" class="form-label">Address of School</label>
                                            <input type="text" class="form-control" id="school_address" name="school_address" required>
                                        </div>
                                    </div>

                                    <!-- Requirements Section -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="requirements" class="form-label">Requirements:</label>
                                            <ul class="ps-3">
                                                <li>F138 (CARD)</li>
                                                <li>Birth Certificate Xerox</li>
                                                <li>Good moral Certificate</li>
                                            </ul>
                                            <input type="file" class="form-control" id="requirements" accept="application/pdf" name="requirements[]" multiple>
                                            <small><i>Only PDF files are allowed.</i></small>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" name="add_registration">Submit</button>
                                    </div>
                                </form>

                                <!-- Enrollment Modal -->
                                <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myLargeModalLabel">
                                                    Enrollment Schedule
                                                </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                    ×
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>There is no enrollment schedule for today.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

		<!-- js -->
        <?php
            include 'footer.php';
        ?>
		<script>
        document.getElementById('grade_level').addEventListener('change', function() {
            var gradeLevelContainer = document.getElementById('grade_level_container');
            var registrationForm = document.getElementById('registrationForm');
            var selectedGradeLevel = this.value;

            if (selectedGradeLevel !== '') {
                fetch('check_enrollment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'grade_level=' + selectedGradeLevel
                })
                .then(response => response.json())
                .then(response => {
                    var enrollmentModal = new bootstrap.Modal(document.getElementById('enrollmentModal'));
                    var modalBody = document.querySelector('#enrollmentModal .modal-body p');
                    
                    if (response.enrollmentExists && response.status === 'Approved') {
                        // Valid enrollment period - show the registration form
                        registrationForm.style.display = 'block';
                        registrationForm.insertBefore(gradeLevelContainer, registrationForm.firstChild);
                    } else {
                        // No valid enrollment - show modal with appropriate message
                        registrationForm.style.display = 'none';
                        
                        if (!response.enrollmentExists) {
                            // No enrollment schedule exists
                            modalBody.textContent = 'There is no enrollment schedule for today.';
                        } else if (response.beforeStart) {
                            // Current date is before start date
                            modalBody.textContent = 'Enrollment for this grade level has not started yet.';
                        } else if (response.afterEnd) {
                            // Current date is after end date
                            modalBody.textContent = 'Enrollment for this grade level has already ended.';
                        } else if (response.status === 'For Review' || response.status === 'Declined') {
                            // Status is not approved
                            modalBody.textContent = 'Enrollment for this grade level is currently ' + response.status.toLowerCase() + '.';
                        }
                        
                        enrollmentModal.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error: Unable to check enrollment schedule. Please try again.');
                });
            } else {
                registrationForm.style.display = 'none';
            }
        });

        document.getElementById('dob').addEventListener('change', function() {
            var dob = new Date(this.value);
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        });

        function triggerFileUpload() {
            document.getElementById('image').click();
        }

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
	</body>
</html>
