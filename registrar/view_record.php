<?php
// Your database connection logic
include 'config.php';

// Start session and error reporting
session_start();
error_reporting(E_ALL);

// Check if the user is logged in
$registrar_id = $_SESSION['registrar_id'];
if (!isset($registrar_id)) {
    header('location:login.php');
    exit; // Stop further execution
}
else {
    $error = ""; // Initialize $error variable
    $msg = ""; // Initialize $error variable
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Registrar</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <?php include 'asset.php';?>

</head>

<body>

    <?php 
    include 'header.php';
    include 'sidebar.php';
    // Fetch user details for editing
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM student WHERE student_id = :id";
        $query = $conn->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $student = $query->fetch(PDO::FETCH_ASSOC);
    }
    // Fetch available grade levels from the database
$sql_grades = "SELECT gradelevel_id, gradelevel_name FROM gradelevel";
$query_grades = $conn->prepare($sql_grades);
$query_grades->execute();
$grades = $query_grades->fetchAll(PDO::FETCH_ASSOC);
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
                        <?php if($error){?>
                                <div class="alert alert-danger" role="alert">
                                    <strong>ERROR</strong>: <?php echo htmlentities($error); ?>
                                </div>
                                <?php } else if($msg){?>
                                <div class="alert alert-success" role="alert">
                                    <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?>
                                </div>
                                <?php }?>
                                <form method="post" action="verify_another.php">
                                <?php if(isset($id)): ?>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
    <?php endif; ?>
                                <div class="row mb-3">
                                <div class="col-md-9" style="margin-top: 120px;">
    <label for="sname" class="form-label">Name</label>
    <input type="text" class="form-control" id="sname" name="sname" value="<?php echo $student['name']; ?>" disabled>
</div>
<div class="col-md-3 text-center">
    <?php if(isset($student['image_path']) && !empty($student['image_path'])): ?>
        <!-- If the student has an image, display it -->
        <img id="preview" src="<?php echo $student['image_path']; ?>" alt="Profile Picture" class="mx-auto d-block" style="width: 200px; height: 200px; cursor: pointer;" onclick="triggerFileUpload()">
    <?php else: ?>
        <!-- If no image is available, display a default placeholder -->
        <img id="preview" src="../images/profile.jpg" alt="Profile Picture" class="mx-auto d-block" style="width: 200px; height: 200px; cursor: pointer;" onclick="triggerFileUpload()">
    <?php endif; ?>
    <input type="file" accept="image/*" class="form-control" id="image" name="image" onchange="previewImage(event)" style="display: none;" disabled>
    <label for="image" class="form-label">Profile</label>
</div>

</div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $student['dob']; ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="pob" class="form-label">Place of Birth</label>
            <input type="text" class="form-control" id="pob" name="pob" value="<?php echo $student['pob']; ?>" disabled>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-1">
            <label for="age" class="form-label">Age</label>
            <input type="text" class="form-control" id="age" name="age" readonly value="<?php echo $student['age']; ?>" disabled>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="father_name" class="form-label">Name of Father</label>
            <input type="text" class="form-control" id="father_name" name="father_name" value="<?php echo $student['father_name']; ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="business_address_father" class="form-label">Business Address (Father)</label>
            <input type="text" class="form-control" id="business_address_father" name="business_address_father" value="<?php echo $student['business_address_father']; ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="telephone_father" class="form-label">Telephone (Father)</label>
            <input type="tel" class="form-control" id="telephone_father" name="telephone_father" value="<?php echo $student['telephone_father']; ?>" disabled>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="mother_name" class="form-label">Name of Mother</label>
            <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?php echo $student['mother_name']; ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="business_address_mother" class="form-label">Business Address (Mother)</label>
            <input type="text" class="form-control" id="business_address_mother" name="business_address_mother" value="<?php echo $student['business_address_mother']; ?>" disabled>
        </div>
        <div class="col-md-6">
            <label for="telephone_mother" class="form-label">Telephone (Mother)</label>
            <input type="tel" class="form-control" id="telephone_mother" name="telephone_mother" value="<?php echo $student['telephone_mother']; ?>" disabled>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="guardian" class="form-label">Guardian (for absent parent/s)</label>
            <input type="text" class="form-control" id="guardian" name="guardian" value="<?php echo $student['guardian']; ?>" disabled>
        </div>
    </div>
    <div class="row mb-3">
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
    <div class="row mb-3">
    <div class="col-md-6">
                                            <label for="grade_level" class="form-label">Grade Level</label>
                                            <select class="form-select" id="grade_level" name="grade_level" disabled>
                                                <?php foreach($grades as $grade): ?>
                                                    <option value="<?php echo $grade['gradelevel_id']; ?>" <?php if($student['grade_level'] == $grade['gradelevel_id']) echo 'selected'; ?>>
                                                        <?php echo $grade['gradelevel_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
    <div class="col-md-6" style="margin-top: 20px;">
    <label for="requirements" class="form-label">Uploaded Requirements:</label>
    <?php
// Fetch file paths from the database
$sql = "SELECT requirements FROM student WHERE student_id = :id";
$query = $conn->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$filesJson = $query->fetchColumn(); // Fetching single column directly
$files = json_decode($filesJson, true); // Decode JSON string into array

// Display files if available
if ($files) {
    echo "<ul>";
    foreach ($files as $file) {
        echo "<li><a href='" . $file . "' target='_blank'>" . basename($file) . "</a></li>";
    }
    echo "</ul>";
}
?>
</div>
<?php if ($student['isVerified'] == 0 || $student['isVerified'] === NULL): ?>
    <div class="text-center">
        <button type="submit" class="btn btn-primary" name="verify_student">Verify Student</button>
    </div>
<?php endif; ?>
</div>
</form>
<?php}?>

                        </div>
                     </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
    
    <?php
    include 'footer.php';
    include 'script.php';
  ?>

</body>

</html>