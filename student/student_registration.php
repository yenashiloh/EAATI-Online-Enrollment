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

$query = $conn->prepare("SELECT * FROM users WHERE id = :student_id");
$query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$query->execute();
$user = $query->fetch(PDO::FETCH_ASSOC);

$full_name = '';
if (isset($user['first_name'])) {
    $full_name = $user['first_name'];
    if (isset($user['last_name'])) {
        $full_name .= ' ' . $user['last_name'];
    }
}

$student_email = '';
if (isset($user['role']) && $user['role'] == 'student') {
    $student_email = isset($user['email']) ? $user['email'] : '';
}

if (isset($_POST['add_registration'])) {

    // get student information from POST
    $grade_level_id = $_POST['grade_level'];
    $sname = $_POST['sname'];
    $dob = $_POST['dob'];
    $pob = $_POST['pob'];
    $email = $_POST['email'];
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

    // get father information from POST
    $father_name = $_POST['father_name'];
    $telephone_father = $_POST['telephone_father'];
    $houseNo_father = $_POST['houseNo_father'];
    $street_father = $_POST['street_father'];
    $barangay_father = $_POST['barangay_father'];
    $municipality_father = $_POST['municipality_father'];

    // get mother information from POST
    $mother_name = $_POST['mother_name'];
    $telephone_mother = $_POST['telephone_mother'];
    $houseNo_mother = $_POST['houseNo_mother'];
    $street_mother = $_POST['street_mother'];
    $barangay_mother = $_POST['barangay_mother'];
    $municipality_mother = $_POST['municipality_mother'];

    // handle image upload
    $uploaded_image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_destination = '../uploads/images/' . $image_name;
        if (move_uploaded_file($image_tmp, $image_destination)) {
            $uploaded_image_path = $image_destination;
        } else {
            $error = "Failed to move uploaded image file: $image_name";
        }
    }

    // handle requirements upload
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

            // insert student information
            $sql = "INSERT INTO student (userId, name, dob, pob, email, age, gender, student_house_number, student_street, 
            student_barangay, student_municipality, guardian, previous_school, school_address, grade_level_id, 
            requirements, image_path, isVerified) 
            VALUES (:student_id, :sname, :dob, :pob, :email, :age, :gender, :student_house_number, :student_street,
            :student_barangay, :student_municipality, :guardian, :previous_school, :school_address, 
            :grade_level_id, :requirements, :image_path, 0)";

            $query = $conn->prepare($sql);
            $query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $query->bindParam(':sname', $sname, PDO::PARAM_STR);
            $query->bindParam(':dob', $dob, PDO::PARAM_STR);
            $query->bindParam(':pob', $pob, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
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
            $query->bindParam(':image_path', $uploaded_image_path, PDO::PARAM_STR);

            $query->execute();
            $new_student_id = $conn->lastInsertId();

            // insert father information
            $father_sql = "INSERT INTO father_information (student_id, father_name, telephone_father, houseNo_father, 
                          street_father, barangay_father, municipality_father) 
                          VALUES (:student_id, :father_name, :telephone_father, :houseNo_father, :street_father, 
                          :barangay_father, :municipality_father)";

            $father_query = $conn->prepare($father_sql);
            $father_query->bindParam(':student_id', $new_student_id, PDO::PARAM_INT);
            $father_query->bindParam(':father_name', $father_name, PDO::PARAM_STR);
            $father_query->bindParam(':telephone_father', $telephone_father, PDO::PARAM_STR);
            $father_query->bindParam(':houseNo_father', $houseNo_father, PDO::PARAM_STR);
            $father_query->bindParam(':street_father', $street_father, PDO::PARAM_STR);
            $father_query->bindParam(':barangay_father', $barangay_father, PDO::PARAM_STR);
            $father_query->bindParam(':municipality_father', $municipality_father, PDO::PARAM_STR);

            $father_query->execute();

            // insert mother information
            $mother_sql = "INSERT INTO mother_information (student_id, mother_name, telephone_mother, houseNo_mother, 
                          street_mother, barangay_mother, municipality_mother) 
                          VALUES (:student_id, :mother_name, :telephone_mother, :houseNo_mother, :street_mother, 
                          :barangay_mother, :municipality_mother)";

            $mother_query = $conn->prepare($mother_sql);
            $mother_query->bindParam(':student_id', $new_student_id, PDO::PARAM_INT);
            $mother_query->bindParam(':mother_name', $mother_name, PDO::PARAM_STR);
            $mother_query->bindParam(':telephone_mother', $telephone_mother, PDO::PARAM_STR);
            $mother_query->bindParam(':houseNo_mother', $houseNo_mother, PDO::PARAM_STR);
            $mother_query->bindParam(':street_mother', $street_mother, PDO::PARAM_STR);
            $mother_query->bindParam(':barangay_mother', $barangay_mother, PDO::PARAM_STR);
            $mother_query->bindParam(':municipality_mother', $municipality_mother, PDO::PARAM_STR);

            $mother_query->execute();

            $conn->commit();
            $msg = "Student Registered Successfully";
        } catch (PDOException $e) {
            $conn->rollBack();
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Student</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include 'asset.php'; ?>

</head>

<body>

    <?php
    include 'header.php';
    include 'sidebar.php';
    ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Student Registration</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Student Registration</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container mt-5">
                                <?php if ($error) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                    </div>
                                <?php } else if ($msg) { ?>
                                    <div class="alert alert-success" role="alert">
                                        <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                    </div>
                                <?php } ?>

                                <?php
                                $query = $conn->prepare("SELECT * FROM student WHERE userId = :student_id");
                                $query->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                                $query->execute();
                                $count = $query->rowCount();
                                $result = $query->fetch(PDO::FETCH_ASSOC);

                                if ($count > 0) {
                                    if ($result['isVerified'] == 0) {
                                ?>
                                        <div class="alert alert-info text-center" style="font-size: 22px;">
                                            Thank you for your submission. We have received your registration.<br>
                                            Please be patient as our registrar verifies your information.<br>
                                            Once your registration is verified, you will receive a confirmation email.<br>
                                            If you have any further questions or concerns, please don't hesitate to reach out to us.
                                        </div>
                                    <?php
                                    } elseif ($result['isVerified'] == 1) {
                                        // Student is already enrolled
                                    ?>
                                        <div class="alert alert-success text-center" style="font-size: 22px;">
                                            Verified. <br><a href="school_fees.php">Click here to proceed to payment.</a>
                                        </div>
                                <?php
                                    }
                                } else {
                                }
                                ?>

                                <!-- Grade Level selection -->

                                <div class="row mb-3" id="grade_level_container">
                                    <div class="col-md-6 offset-md-3 text-center">
                                        <label for="grade_level" class="form-label">Grade Level</label>
                                        <select class="form-select" id="grade_level" name="grade_level">
                                            <option value="">Select Grade Level</option>
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
                                    <!-- Personal Information Section -->
                                    <div class="row mb-3 justify-content-center">
                                        <div class="col-md-12 text-center d-flex justify-content-center">
                                            <div>
                                                <img id="preview" src="../images/profile.jpg" alt="Profile Picture" class="img-fluid" style="width: 150px; height: 150px; cursor: pointer;" onclick="triggerFileUpload()">
                                                <input type="file" accept="image/*" class="form-control" id="image" name="image" onchange="previewImage(event)" style="display: none;" required>
                                                <label for="image" class="form-label mt-2">Upload Image (2x2)</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="sname" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="sname" name="sname" value="<?php echo htmlentities($full_name); ?>" style="pointer-events: none;" required>
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
                                            <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($student_email) ? htmlspecialchars($student_email) : ''; ?>" required>
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
                                            <label for="telephone_father" class="form-label">Telephone (Father)</label>
                                            <input type="tel" class="form-control" id="telephone_father" name="telephone_father" required>
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
                                            <input type="tel" class="form-control" id="telephone_mother" name="telephone_mother" required>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Enrollment Modal -->
            <div class="modal fade" id="enrollmentModal" tabindex="-1" aria-labelledby="enrollmentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="enrollmentModalLabel">Enrollment Schedule</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            There is no enrollment schedule for today.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
    <?php include 'script.php'; ?>

    <script>
        // Your existing JavaScript code for grade level selection
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
                    if (response.status === 'For Review' || response.status === 'Declined' || !response.enrollmentExists) {
                        var enrollmentModal = new bootstrap.Modal(document.getElementById('enrollmentModal'));
                        enrollmentModal.show();
                        registrationForm.style.display = 'none';
                    } else {
                        registrationForm.style.display = 'block';
                        registrationForm.insertBefore(gradeLevelContainer, registrationForm.firstChild);
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

        // Calculate age based on date of birth
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

        // Image preview functions
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