<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if (!isset($registrar_id)) {
    header('location:login.php');
    exit; // Ensure no further execution
}

try {
    // Fetch grade levels from the database
    $stmt = $conn->prepare("
        SELECT gradelevel_id, gradelevel_name, gradelevel_description 
        FROM gradelevel 
        ORDER BY CAST(SUBSTRING_INDEX(gradelevel_name, ' ', -1) AS UNSIGNED) ASC
    ");
    $stmt->execute();
    $gradelevels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<style>
    .modal-body {
        max-height: 400px; /* Adjust this value based on your needs */
        overflow-y: auto;
    }
</style>
<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset=
		<title>Section</title>

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
									<h4>Section</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="teacher_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Section
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <?php
                    // Check if the 'deleted' parameter is set and equals to 1
                    if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
                        echo "<div class='alert alert-success'>Section Deleted Successfully!</div>";
                    }
                    ?>
                    <?php
                    // Check if the 'added' parameter is set and equals to 1
                    if (isset($_GET['added']) && $_GET['added'] == 1) {
                        echo "<div class='alert alert-success'>New Section Added Successfully!</div>";
                    }
                    ?>
                    <?php
                    // Check if the 'edited' parameter is set and equals to 1
                    if (isset($_GET['edited']) && $_GET['edited'] == 1) {
                        echo "<div class='alert alert-success'>Updated Successfully!</div>";
                    }
                    ?>
                
                    <?php
                    if (isset($_GET['error']) && $_GET['error'] == 1) {
                        echo "<div class='alert alert-danger'>Section already exists for this grade level!</div>";
                    }
                    ?>

                                        
                    <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#addSubjectModal">
                        <i class="fa fa-plus"></i> Add Section
                    </button>
                    <?php
                        // Include config file
                        require_once "config1.php";
                        // Attempt select query execution
                        $sql = "SELECT sections.*, gradelevel.gradelevel_name 
                        FROM sections 
                        JOIN gradelevel ON sections.gradelevel_id = gradelevel.gradelevel_id";
                
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table class="data-table table stripe hover nowrap">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>No.</th>";
                                echo "<th>Grade Level</th>";
                                echo "<th>Section Name</th>";
                                echo "<th>Section Description</th>";
                                echo "<th>Section Capacity</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                $counter = 1; 
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $counter . "</td>"; 
                                    echo "<td>" . $row['gradelevel_name'] . "</td>";
                                    echo "<td>" . $row['section_name'] . "</td>";
                                    echo "<td>" . $row['section_description'] . "</td>";
                                    echo "<td>" . $row['sectionCapacity'] . "</td>";
                                    echo "<td>";
                                    echo '<a href="#" class="edit-btn" data-toggle="modal" data-target="#editSectionModal' . $row['section_id'] . '"><span class="bi bi-pencil-fill" style="font-size: 18px;"></span></a>';
                                   // Here's the corrected Edit Section Modal code
                                    echo '<div class="modal fade" id="editSectionModal' . $row['section_id'] . '" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel' . $row['section_id'] . '" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myLargeModalLabel">Edit Section</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            </div>
                                            <form method="post" action="edit_section.php">
                                                <div class="modal-body">';
                                                
                                    // Fetch grade levels for the dropdown - moved outside the echo statement
                                    $grade_sql = "SELECT gradelevel_id, gradelevel_name FROM gradelevel ORDER BY CAST(SUBSTRING_INDEX(gradelevel_name, ' ', -1) AS UNSIGNED) ASC";
                                    $grade_result = mysqli_query($link, $grade_sql);

                                    echo '              <div class="mb-3">
                                                    <label for="editGradelevelId' . $row['section_id'] . '" class="form-label">Grade Level</label>
                                                    <select class="form-control" id="editGradelevelId' . $row['section_id'] . '" name="editGradelevelId" required>';
                                                    
                                    if ($grade_result) {
                                    while ($grade_row = mysqli_fetch_array($grade_result)) {
                                    $selected = ($grade_row['gradelevel_id'] == $row['gradelevel_id']) ? 'selected' : '';
                                    echo '<option value="' . $grade_row['gradelevel_id'] . '" ' . $selected . '>' . $grade_row['gradelevel_name'] . '</option>';
                                    }
                                    }

                                    echo '              </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editSectionName' . $row['section_id'] . '" class="form-label">Section Name</label>
                                                    <input type="text" class="form-control" id="editSectionName' . $row['section_id'] . '" name="editSectionName" value="' . $row['section_name'] . '" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editSectionDescription' . $row['section_id'] . '" class="form-label">Section Description</label>
                                                    <textarea class="form-control" id="editSectionDescription' . $row['section_id'] . '" name="editSectionDescription" rows="3" required>' . $row['section_description'] . '</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editSectionCapacity' . $row['section_id'] . '" class="form-label">Section Capacity</label>
                                                    <input type="number" class="form-control" id="editSectionCapacity' . $row['section_id'] . '" name="editSectionCapacity" value="' . $row['sectionCapacity'] . '" required>
                                                </div>
                                                <input type="hidden" name="section_id" value="' . $row['section_id'] . '">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                    </div>
                                    </div>';
                                    echo '<a href="#" data-toggle="modal" data-target="#deleteModal' . $row['section_id'] . '" title="Delete Record"><span class="bi bi-trash-fill ml-2" style="font-size: 18px;"></span></a>';
                                    // Delete Modal
                                    echo '<div class="modal fade" id="deleteModal' . $row['section_id'] . '" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel' . $row['section_id'] . '" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="myLargeModalLabel">Confirm Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this section?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <a href="delete_section.php?id=' . $row['section_id'] . '" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    echo "</td>";
                                    echo "</tr>";
                                    $counter++; // Increment counter for next row
                                }
                                echo "</tbody>";
                                echo "</table>";
                                // Free result set
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                        // Close connection
                        mysqli_close($link);
                        ?>
                    </div>
                </div>
            </div>
        </div>
                <!-- Add Section Modal -->
                <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myLargeModalLabel">Add Section</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <form method="post" action="add_section.php">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="gradeLevel" class="form-label">Grade Level</label>
                                        <select class="form-control" id="gradeLevel" name="gradelevel_id" required>
                                            <option value="">Select Grade Level</option>
                                            <?php foreach ($gradelevels as $gradelevel): ?>
                                                <option value="<?= htmlspecialchars($gradelevel['gradelevel_id']) ?>">
                                                    <?= htmlspecialchars($gradelevel['gradelevel_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sectionName" class="form-label">Section Name</label>
                                        <input type="text" class="form-control" id="sectionName" name="sectionName" value="Section 1" >
                                    </div>
                                    <div class="mb-3">
                                        <label for="sectionDescription" class="form-label">Section Description</label>
                                        <textarea class="form-control" id="sectionDescription" name="sectionDescription" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sectionCapacity" class="form-label">Section Capacity</label>
                                        <input type="number" class="form-control" id="sectionCapacity" name="sectionCapacity" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

		<?php
            include 'footer.php';
        ?>
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
