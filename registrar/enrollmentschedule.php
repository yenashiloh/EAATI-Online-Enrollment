<?php

include 'config.php';

session_start();

$registrar_id = $_SESSION['registrar_id'];

if (!isset($registrar_id)) {
    header('location:../login.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset="utf-8" />
        <title>Enrollment</title>

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
                                    <h4>Enrollment</h4>
                                </div>
                                <nav aria-label="breadcrumb" role="navigation">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="registrar_dashboard.php">Menu</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            Enrollment
                                        </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30 text-left">
                    <?php
                        if (isset($_GET['updated']) && $_GET['updated'] == 1) { 
                            echo "<div class='alert alert-success'>Enrollment Schedule Updated Successfully!</div>";
                        } elseif (isset($_GET['added']) && $_GET['added'] == 1) {
                            echo "<div class='alert alert-success'>New Enrollment Schedule Added Successfully!</div>";
                        }
                        ?>


                        <!-- Add Modal -->
                        <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addSubjectModalLabel">
                                          Add Enrollment Schedule
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            ×
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="dateError" class="text-danger" style="display: none;">End date should be greater than start date.</div>
                                        <form id="enrollmentForm" method="post" action="add_enrollmentschedule.php">
                                            <div class="mb-3">
                                                <label for="gradeGroup" class="form-label d-block">Grade Level Group</label>
                                                <select class="custom-select col-12" id="gradeGroup" name="gradeGroup" required>
                                                    <option value="">Select Grade Group</option>
                                                    <option value="1-3">Grades 1-3</option>
                                                    <option value="4-6">Grades 4-6</option>
                                                    <option value="7-8">Grades 7-8</option>
                                                    <option value="9-10">Grades 9-10</option>
                                                </select>
                                                <input type="hidden" id="selectedGradeIds" name="selectedGradeIds">
                                            </div>
                                            <div class="mb-3">
                                                <label for="startDate" class="form-label">Start Date</label>
                                                <input type="date" class="form-control" id="startDate" name="startDate" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="endDate" class="form-label">End Date</label>
                                                <input type="date" class="form-control" id="endDate" name="endDate" required>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pd-20">
                            <h4 class="h4 mb-1">Enrollment</h4>
                        </div>
                        <div class="pb-20">
                            <script>
                                document.getElementById('enrollmentForm').addEventListener('submit', function(event) {
                                    var startDate = new Date(document.getElementById('startDate').value);
                                    var endDate = new Date(document.getElementById('endDate').value);

                                    if (endDate < startDate) {
                                        event.preventDefault();
                                        document.getElementById('dateError').style.display = 'block';
                                    } else {
                                        document.getElementById('dateError').style.display = 'none';
                                    }
                                });
                            </script>
                            <?php
                            include_once 'config1.php';

                            $gradeGroups = [
                                '1-3' => ['Grade 1', 'Grade 2', 'Grade 3'],
                                '4-6' => ['Grade 4', 'Grade 5', 'Grade 6'],
                                '7-8' => ['Grade 7', 'Grade 8'],
                                '9-10' => ['Grade 9', 'Grade 10']
                            ];

                            echo '<table class="table table-striped">
                                  <thead>
                                    <tr>
                                      <th>Grade Group</th>
                                      <th>Start Date</th>
                                      <th>End Date</th>
                                      <th>Status</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody>';

                            foreach ($gradeGroups as $groupName => $grades) {
                                echo '<tr>';
                                echo '<td>Grades ' . $groupName . '</td>';

                                $gradeNamesStr = "'" . implode("', '", $grades) . "'";
                                $sql = "SELECT gl.gradelevel_name, es.start_date, es.end_date, es.status 
                                        FROM enrollmentschedule es
                                        JOIN gradelevel gl ON es.gradelevel_id = gl.gradelevel_id
                                        WHERE gl.gradelevel_name IN ($gradeNamesStr)
                                        GROUP BY gl.gradelevel_name";

                                $result = mysqli_query($link, $sql);

                                if (mysqli_num_rows($result) > 0) {
                                    $firstRow = mysqli_fetch_assoc($result);
                                    $allSame = true;
                                    $startDate = $firstRow['start_date'];
                                    $endDate = $firstRow['end_date'];
                                    $status = $firstRow['status'];

                                    mysqli_data_seek($result, 0);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        if ($row['start_date'] != $startDate || $row['end_date'] != $endDate || $row['status'] != $status) {
                                            $allSame = false;
                                            break;
                                        }
                                    }

                                    if ($allSame) {
                                        echo '<td>' . date('M d, Y', strtotime($startDate)) . '</td>';
                                        echo '<td>' . date('M d, Y', strtotime($endDate)) . '</td>';
                                        echo '<td>' . $status . '</td>';
                                        echo '<td>
                                        <a href="#" 
                                           class="m-2 edit-schedule" 
                                           data-group="' . htmlspecialchars($groupName) . '" 
                                           data-start="' . htmlspecialchars($startDate) . '" 
                                           data-end="' . htmlspecialchars($endDate) . '" 
                                           title="Edit Schedule" 
                                           data-toggle="tooltip">
                                           <span class="bi bi-pencil-fill" style="font-size: 18px;"></span>
                                        </a>                                
                                      </td>';
                                
                                    } else {
                                        echo '<td colspan="3"><span class="badge badge-warning">Mixed Schedules</span></td>';
                                        echo '<td>
                                                <a href="#" class="btn btn-primary btn-sm edit-group" data-group="' . $groupName . '">Unify</a>
                                              </td>';
                                    }
                                } else {
                                    echo '<td colspan="3"><span class="badge badge-secondary">Not Scheduled</span></td>';
                                    echo '<td>
                                            <a href="#" class="bi bi-plus-square-fill add-group" data-toggle="modal"  style="font-size: 18px;"
                                               data-target="#addSubjectModal" data-group="' . $groupName . '">
                                            </a>
                                          </td>';
                                }

                                echo '</tr>';
                            }

                            echo '</tbody></table>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubjectModalLabel">
                            Edit Enrollment Schedule
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            ×
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="editDateError" class="text-danger" style="display: none;">End date should be greater than start date.</div>
                        <form id="editEnrollmentForm" method="post" action="edit_enrollmentschedule.php">
                            <input type="hidden" id="editGroup" name="editGroup"> 

                            <div class="mb-3">
                                <label for="editGradeGroup" class="form-label d-block">Grade Level Group</label>
                                <select class="custom-select col-12" id="editGradeGroup" name="editGradeGroup" required disabled>
                                    <option value="">Select Grade Group</option>
                                    <option value="1-3">Grades 1-3</option>
                                    <option value="4-6">Grades 4-6</option>
                                    <option value="7-8">Grades 7-8</option>
                                    <option value="9-10">Grades 9-10</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editStartDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="editStartDate" name="editStartDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEndDate" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="editEndDate" name="editEndDate" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-primary">Update</button>
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
<script>
 document.addEventListener('DOMContentLoaded', function() {
    // Date validation
    document.getElementById('enrollmentForm').addEventListener('submit', function(event) {
        var startDate = new Date(document.getElementById('startDate').value);
        var endDate = new Date(document.getElementById('endDate').value);

        if (endDate < startDate) {
            event.preventDefault();
            document.getElementById('dateError').style.display = 'block';
        } else {
            document.getElementById('dateError').style.display = 'none';
        }
    });

    // Setup add-group links
    document.querySelectorAll('.add-group').forEach(function(button) {
        button.addEventListener('click', function() {
            var gradeGroup = this.getAttribute('data-group');
            document.getElementById('gradeGroup').value = gradeGroup;
        });
    });

    // Setup edit-schedule links
    document.querySelectorAll('.edit-schedule').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var gradeGroup = this.getAttribute('data-group');
            var startDate = this.getAttribute('data-start');
            var endDate = this.getAttribute('data-end');
            
            // Format the dates to YYYY-MM-DD for the input fields
            var formattedStartDate = new Date(startDate).toISOString().split('T')[0];
            var formattedEndDate = new Date(endDate).toISOString().split('T')[0];
            
            // Set the values in the modal
            document.getElementById('gradeGroup').value = gradeGroup;
            document.getElementById('startDate').value = formattedStartDate;
            document.getElementById('endDate').value = formattedEndDate;
            
            // Show the modal
            $('#addSubjectModal').modal('show');
        });
    });


        // Prevent invalid date selection
        document.getElementById("editEnrollmentForm").addEventListener("submit", function (event) {
            var startDate = new Date(document.getElementById("editStartDate").value);
            var endDate = new Date(document.getElementById("editEndDate").value);

            if (endDate < startDate) {
                event.preventDefault();
                document.getElementById("editDateError").style.display = "block";
            } else {
                document.getElementById("editDateError").style.display = "none";
            }
        });
    });
</script>
