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

                        // Attempt select query execution
                        $sql = "SELECT * from rooms";
                        if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<table class="data-table table stripe hover nowrap">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>#</th>";
                                            echo "<th>Room Name</th>";
                                            echo "<th>Room Description</th>";
                                            echo "<th>Action</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                            echo "<td>" . $row['room_id'] . "</td>";
                                            echo "<td>" . $row['room_name'] . "</td>";
                                            echo "<td>" . $row['room_description'] . "</td>";
                                            echo "<td>";
                                            
                                            echo '<a href="#" class="edit-btn" data-toggle="modal" data-target="#editRoomModal'.$row['room_id'].'"><span class="bi bi-pencil-fill"  style="font-size: 18px; "></span></a>';
                                            
                                            // Edit Room Modal
                                            echo '<div class="modal fade" id="editRoomModal'.$row['room_id'].'" tabindex="-1" role="dialog" aria-labelledby="editRoomModalLabel'.$row['room_id'].'" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editRoomModalLabel'.$row['room_id'].'">Edit Room</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Form to edit room -->
                                                            <form method="post" action="edit_room.php">
                                                                <div class="mb-3">
                                                                    <label for="editRoomName" class="form-label">Room Name</label>
                                                                    <input type="text" class="form-control" id="editRoomName" name="editRoomName" value="'.$row['room_name'].'" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="editRoomDescription" class="form-label">Room Description</label>
                                                                    <textarea class="form-control" id="editRoomDescription" name="editRoomDescription" rows="3" required>'.$row['room_description'].'</textarea>
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
                                            
                                            echo '<a href="#" data-toggle="modal" data-target="#deleteModal'.$row['room_id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill ml-2" style="font-size: 18px; "></span></a>';
                                    
                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['room_id'].'" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel'.$row['room_id'].'" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel'.$row['room_id'].'">Confirm Delete</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
		<!-- End Google Tag Manager (noscript) -->
	</body>
</html>
