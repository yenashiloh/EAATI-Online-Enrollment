<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if(!isset($registrar_id)){
   header('location:login.php');
   exit; // Add exit to stop further execution
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
?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Room Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Room</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['deleted']) && $_GET['deleted'] == 1){
                            echo "<div class='alert alert-success'>Room deleted successfully.</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['added']) && $_GET['added'] == 1){
                            echo "<div class='alert alert-success'>New Room Added Successfully.</div>";
                        }
                        ?>
                        <?php
                        // Check if the 'deleted' parameter is set and equals to 1
                        if(isset($_GET['edited']) && $_GET['edited'] == 1){
                            echo "<div class='alert alert-success'>Updated Successfully.</div>";
                        }
                        ?>

                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-success pull-right" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Room
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addSubjectModalLabel">Add Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Include config file
                    require_once "config1.php";

                    // Attempt select query execution
                    $sql = "SELECT * from rooms";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table datatable">';
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
                                        echo '<a href="#" class="r-2 view-btn" data-bs-toggle="modal" data-bs-target="#viewRoomModal'.$row['room_id'].'" title="View Record" data-toggle="tooltip"><span class="bi bi-eye-fill"></span></a>';

                                        // View Room Modal
                                        echo '
                                        <div class="modal fade" id="viewRoomModal'.$row['room_id'].'" tabindex="-1" aria-labelledby="viewRoomModalLabel'.$row['room_id'].'" aria-hidden="true">
                                          <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="viewRoomModalLabel'.$row['room_id'].'">View Room</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                <h5>Room Name: ' . $row['room_name'] . '</h5>
                                                <p>Room Description: ' . $row['room_description'] . '</p>
                                                <!-- Add any additional information you want to display here -->
                                              </div>
                                            </div>
                                          </div>
                                        </div>';
                                        
                                        echo '<a href="#" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editRoomModal'.$row['room_id'].'"><span class="bi bi-pencil-fill"></span></a>';
                                        
                                            // Edit Grade Level Modal
                                            echo '<div class="modal fade" id="editRoomModal'.$row['room_id'].'" tabindex="-1" aria-labelledby="editRoomModalLabel'.$row['room_id'].'" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="Room'.$row['room_id'].'">Edit Room</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Form to edit grade level -->
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
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';                                            echo '<a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal'.$row['room_id'].'" title="Delete Record" data-toggle="tooltip"><span class="bi bi-trash-fill"></span></a>';

                                            // Delete Modal
                                            echo '
                                            <div class="modal fade" id="deleteModal'.$row['room_id'].'" tabindex="-1" aria-labelledby="deleteModalLabel'.$row['room_id'].'" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel'.$row['room_id'].'">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body">
                                                    Are you sure you want to delete this room?
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
    </section>

</main><!-- End #main -->

<?php
    include 'footer.php';
    include 'script.php';
?>

</body>

</html>
