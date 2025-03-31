<?php
include 'config.php';
session_start();
$registrar_id = $_SESSION['registrar_id'];
if(!isset($registrar_id)){
   header('location:../login.php');
   exit; 
}

if(isset($_GET['verified']) && $_GET['verified'] == 1){
    $id = $_GET['id']; 

    $sql = "UPDATE student SET isVerified = 1 WHERE student_id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $success_message = "Student Verified Successfully!";
}
    ?>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Room</title>

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
									<h4>Room</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="teacher_dashboard.php">Menu</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Room
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
		
                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
                            echo "<div class='alert alert-success'>Room deleted successfully!</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['added']) && $_GET['added'] == 1){
                            echo "<div class='alert alert-success'>New Room Added Successfully!</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['edited']) && $_GET['edited'] == 1){
                            echo "<div class='alert alert-success'>Updated Successfully!</div>";
                        }
                        ?>

                        <button type="button" class="btn btn-success mb-4" data-toggle="modal" data-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Room
                        </button>
                        
                        <div class="pb-20">
                        <?php
                        // Include config file
                        require_once "config1.php";

                        // Fetch grade levels for the dropdown
                        $gradeLevels = [];
                        $gradeQuery = "SELECT gradelevel_id, gradelevel_name FROM gradelevel ORDER BY CAST(SUBSTRING_INDEX(gradelevel_name, ' ', -1) AS UNSIGNED) ASC";
                        $gradeResult = mysqli_query($link, $gradeQuery);
                        while ($gradeRow = mysqli_fetch_assoc($gradeResult)) {
                            $gradeLevels[] = $gradeRow;
                        }

                        // Attempt select query execution
                        $sql = "SELECT r.room_id, r.room_name, r.room_description, g.gradelevel_name, r.gradelevel_id 
                                FROM rooms r
                                LEFT JOIN gradelevel g ON r.gradelevel_id = g.gradelevel_id
                                ORDER BY CAST(SUBSTRING_INDEX(g.gradelevel_name, ' ', -1) AS UNSIGNED) ASC";
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<table class="data-table table stripe hover nowrap">';
                                echo "<thead><tr><th>#</th><th>Room Name</th><th>Room Description</th><th>Grade Level</th><th>Action</th></tr></thead><tbody>";
                                
                                $counter = 1; 
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr><td>{$counter}</td><td>{$row['room_name']}</td><td>{$row['room_description']}</td><td>" . ($row['gradelevel_name'] ?? 'N/A') . "</td><td>";
                                    
                                    // Edit button
                                    echo '<a href="#" class="edit-btn" data-toggle="modal" data-target="#editRoomModal'.$row['room_id'].'"><span class="bi bi-pencil-fill" style="font-size: 18px;"></span></a>';
                                    
                                    // Edit Room Modal
                                    echo '<div class="modal fade" id="editRoomModal'.$row['room_id'].'" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Room</h5>
                                                        <button type="button" class="close" data-dismiss="modal">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="edit_room.php">
                                                          <div class="mb-3">
                                                                <label for="editGradeLevel" class="form-label">Grade Level</label>
                                                                <select class="form-control" name="editGradeLevel" required>';
                                                                    foreach ($gradeLevels as $grade) {
                                                                        $selected = ($grade['gradelevel_id'] == $row['gradelevel_id']) ? 'selected' : '';
                                                                        echo '<option value="'.$grade['gradelevel_id'].'" '.$selected.'>'.$grade['gradelevel_name'].'</option>';
                                                                    }
                                                        echo ' </select>
                                                            </div>
                                                            
                                                            <div class="mb-3">
                                                                <label for="editRoomName" class="form-label">Room Name</label>
                                                                <input type="text" class="form-control" name="editRoomName" value="'.$row['room_name'].'" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editRoomDescription" class="form-label">Room Description</label>
                                                                <textarea class="form-control" name="editRoomDescription" rows="3" required>'.$row['room_description'].'</textarea>
                                                            </div>
                                                          
                                                            <input type="hidden" name="room_id" value="'.$row['room_id'].'">
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    
                                    // Delete button
                                    echo '<a href="#" data-toggle="modal" data-target="#deleteModal'.$row['room_id'].'" title="Delete Record"><span class="bi bi-trash-fill ml-2" style="font-size: 18px;"></span></a>';
                                    
                                    // Delete Modal
                                    echo '<div class="modal fade" id="deleteModal'.$row['room_id'].'" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal">×</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this room?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <a href="delete_room.php?id='.$row['room_id'].'" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    
                                    echo "</td></tr>";
                                    $counter++;
                                }
                                echo "</tbody></table>";
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

            <!-- Add Room Modal -->
            <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSubjectModalLabel">Add Room</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <!-- Form to add subject -->
                            <form method="post" action="add_room.php">
                            <?php
                            include 'config.php';

                                $sql = "SELECT gradelevel_id, gradelevel_name 
                                FROM gradelevel 
                                ORDER BY CAST(SUBSTRING_INDEX(gradelevel_name, ' ', -1) AS UNSIGNED) ASC";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $gradelevels = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                                <div class="mb-3">
                                    <label for="gradeLevelName" class="form-label">Grade Level Name</label>
                                    <select class="form-control" id="gradeLevelName" name="gradeLevelID" required>
                                        <option value="">Select Grade Level</option>
                                        <?php foreach ($gradelevels as $grade) : ?>
                                            <option value="<?= $grade['gradelevel_id']; ?>">
                                                <?= htmlspecialchars($grade['gradelevel_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="roomName" class="form-label">Room Name</label>
                                    <input type="text" class="form-control" id="roomName" name="roomName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="roomDescription" class="form-label">Room Description</label>
                                    <textarea class="form-control" id="roomDescription" name="roomDescription" rows="3" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

		<?php
            include 'footer.php';
        ?>
	</body>
</html>
