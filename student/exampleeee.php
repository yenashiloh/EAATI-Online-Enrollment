<?php
                        require_once "config1.php";
                        $sql = "SELECT * FROM student INNER JOIN users ON student.userId = users.id";
                        if ($result = mysqli_query($link, $sql)) {
                            if (mysqli_num_rows($result) > 0) {
                                echo '<table class="data-table table stripe hover nowrap">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>No.</th>"; 
                                echo "<th>Name</th>";
                                echo "<th>Date of Birth</th>";
                                echo "<th>Email</th>";
                                echo "<th>Status</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $no = 1; 
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['dob'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . ($row['isVerified'] == 1 ? 'Verified' : 'Not Verified') . "</td>";

                                    echo "<td>";
                                    echo '<a href="view_record.php?id=' . $row['student_id'] . '" title="View Student" data-toggle="tooltip">
                                            <span class="bi bi-eye-fill" style="font-size: 20px;"></span>
                                        </a>';
                                    echo '  ';

                                    if ($row['isVerified'] == 0) {
                                        echo '<a href="students.php?verified=1&id=' . $row['student_id'] . '" class="btn btn-success" title="Verify">
                                                <span class="bi bi-check-circle" style="font-size: 18px;"></span>
                                            </a>';
                                    }
                                    echo '  ';

                                    echo '<a href="#" data-toggle="modal" data-target="#Medium-modal' . $row['id'] . '" title="Delete Record" data-toggle="tooltip">
                                            <span class="bi bi-trash-fill" style="font-size: 18px;"></span>
                                        </a>';

                                    // Delete Confirmation Modal
                                    echo '
                                    <div class="modal fade" id="Medium-modal' . $row['id'] . '" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel' . $row['id'] . '" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Confirm Delete</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete this record?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <a href="delete.php?id=' . $row['id'] . '" class="btn btn-primary">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                    echo "</td>";
                                    echo "</tr>";
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