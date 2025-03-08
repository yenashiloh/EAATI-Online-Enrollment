<?php
include 'config.php';
include 'config1.php';
session_start();
$registrar_id = $_SESSION['registrar_id'] ?? null;
if(!isset($registrar_id)){
   header('location:../login.php');
   exit; 
}
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    
    $student_query = "SELECT s.*, u.first_name, u.last_name, u.email, u.contact_number, u.username
                      FROM student s
                      LEFT JOIN users u ON s.userid = u.id
                      WHERE s.student_id = ?";
    $stmt = $link->prepare($student_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $student_result = $stmt->get_result();
    $student = $student_result->fetch_assoc();
    
    if (!$student) {
        $_SESSION['error'] = "Student not found";
        header('location:students.php');
        exit;
    }
    
    $grades_query = "SELECT eg.*, s.subject_name, s.section_id 
                     FROM encodedgrades eg 
                     INNER JOIN schedules s ON eg.schedule_id = s.id 
                     WHERE eg.student_id = ?";
    $stmt = $link->prepare($grades_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $grades_result = $stmt->get_result();
    
    $grades_by_section = [];
    while ($grade = $grades_result->fetch_assoc()) {
        $section_id = $grade['section_id'];
        if (!isset($grades_by_section[$section_id])) {
            $grades_by_section[$section_id] = [];
        }
        $grades_by_section[$section_id][] = $grade;
    }
    
    $sections_info = [];
    foreach (array_keys($grades_by_section) as $section_id) {
        $section_query = "SELECT s.section_name, g.gradelevel_name 
                         FROM sections s
                         INNER JOIN gradelevel g ON s.gradelevel_id = g.gradelevel_id
                         WHERE s.section_id = ?";
        $stmt = $link->prepare($section_query);
        $stmt->bind_param("i", $section_id);
        $stmt->execute();
        $section_result = $stmt->get_result();
        $section_info = $section_result->fetch_assoc();
        
        if ($section_info) {
            $sections_info[$section_id] = [
                'section_name' => $section_info['section_name'],
                'gradelevel_name' => $section_info['gradelevel_name']
            ];
        }
    }
    
    ksort($grades_by_section);
    
} else {
    $_SESSION['error'] = "No student ID provided";
    header('location:students.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form 138</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
</link>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-left {
            float: left;
            max-width: 80px;
        }
        .logo-right {
            float: right;
            max-width: 80px;
            margin-top: -90px;
        }
        .form-title {
            font-weight: bold;
            margin-top: 10px;
        }
        .form-subtitle {
            font-size: 13px;
            font-style: italic;
        }
        .section-header {
            background-color: #e0e0e0;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        .underline {
            border-bottom: 1px solid #000;
            min-height: 20px;
            position: relative;
        }
        .underline-data {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5px;
            text-align: center;
        }
        .left-align {
            text-align: left;
        }
        .form-check {
            display: inline-block;
        }
        .print-button {
            margin: 20px 0;
        }
        .grade-section {
            margin-bottom: 40px;
        }
        @media print {
            .print-button, .btn {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
                font-size: 12px; 
            }
            .container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
            @page {
                size: landscape; 
                margin: 10mm;
            }
        }

    </style>
</head>
<body>
    <div class="container mt-4">
        <button class="btn btn-primary print-button" onclick="window.print()">Print Form</button>
        
        <div class="header">
            <img src="../asset/img/DepEd-logo.webp" class="logo-left" alt="DepEd Logo">
            <div class="text-center">
                <div>Republic of the Philippines</div>
                <div>Department of Education</div>
                <div class="form-title">Learner Permanent Record for Elementary School (SF10-ES)</div>
                <div class="form-subtitle">(Formerly Form 137)</div>
            </div>
            <img src="../asset/img/deped-logo-1.jpg" class="logo-right" alt="DepEd Logo">
        </div>
        
        <div class="clearfix"></div>
        
        <!-- LEARNER'S PERSONAL INFORMATION -->
        <div class="section-header">LEARNER'S PERSONAL INFORMATION</div>
        
        <div class="row mb-2">
            <div class="col-3">
                <label>LAST NAME:</label>
                <div class="underline">
                    <div class="underline-data">
                        <?php 
                        echo isset($student['last_name']) 
                            ? strtoupper(htmlspecialchars($student['last_name'])) 
                            : ''; 
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-3">
                <label>FIRST NAME:</label>
                <div class="underline">
                    <div class="underline-data">
                        <?php 
                        echo isset($student['first_name']) 
                            ? strtoupper(htmlspecialchars($student['first_name'])) 
                            : ''; 
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-3">
                <label>NAME EXTN. (Jr./II):</label>
                <div class="underline">
                    <div class="underline-data"></div>
                </div>
            </div>
            <div class="col-3">
                <label>MIDDLE NAME:</label>
                <div class="underline">
                    <div class="underline-data"></div>
                </div>
            </div>
        </div>
        
        <div class="row mb-2">
            <div class="col-8">
                <label>Learner Reference Number (LRN):</label>
                <div class="underline">
                    <div class="underline-data"> <?php 
                    echo isset($student['username']) 
                        ? strtoupper(htmlspecialchars($student['username'])) 
                        : ''; 
                    ?></div>
                </div>
            </div>
            <div class="col-2">
                <label>Birthdate (mm/dd/yyyy):</label>
                <div class="underline">
                    <div class="underline-data"><?php echo isset($student['dob']) ? strtoupper(htmlspecialchars($student['dob'])) : ''; ?></div>
                </div>
            </div>
            <div class="col-2">
                <label>Sex:</label>
                <div class="underline">
                <div class="underline-data"><?php echo isset($student['gender']) ? strtoupper(htmlspecialchars($student['gender'])) : ''; ?></div>
                </div>
            </div>
        </div>
        
        <!-- ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLLMENT -->
        <div class="section-header">ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLLMENT</div>
        
        <div class="row mb-2">
            <div class="col-4">
                <label>Credential Presented for Grade 1:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" checked>
                    <label class="form-check-label">Kinder Progress Report</label>
                </div>
                <div class="form-check ml-3">
                    <input class="form-check-input" type="checkbox" value="">
                    <label class="form-check-label">ECCD Checklist</label>
                </div>
                <div class="form-check ml-3">
                    <input class="form-check-input" type="checkbox" value="" checked>
                    <label class="form-check-label">Kindergarten Certificate of Completion</label>
                </div>
            </div>
            <div class="col-4">
                <label>Name of School:</label>
                <div class="underline">
                    <div class="underline-data">EASTERN ACHIEVER ACADEMY OF TAGUIG INC.</div>
                </div>
                <label>School ID:</label>
                <div class="underline">
                    <div class="underline-data"><?php 
                    echo isset($student['username']) 
                        ? strtoupper(htmlspecialchars($student['username'])) 
                        : ''; 
                    ?></div>
                </div>
            </div>
            <div class="col-4">
                <label>Address of School:</label>
                <div class="underline">
                    <div class="underline-data">CARLOS, P. GARCIA AVENUE, TAGUIG CITY</div>
                </div>
            </div>
        </div>
        
        <div class="row mb-2">
            <div class="col-3">
                <label>Other Credential Presented:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="">
                    <label class="form-check-label">PEPT Passer Rating:</label>
                </div>
                <div class="underline">
                    <div class="underline-data"></div>
                </div>
            </div>
            <div class="col-4">
                <label>Date of Examination/Assessment (mm/dd/yyyy):</label>
                <div class="underline">
                    <div class="underline-data"></div>
                </div>
            </div>
            <div class="col-5">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="">
                    <label class="form-check-label">Others (Pls. Specify):</label>
                </div>
                <div class="underline">
                    <div class="underline-data"></div>
                </div>
                <label>Remark:</label>
                <div class="underline">
                    <div class="underline-data"></div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <label>Name and Address of Testing Center:</label>
                <div class="underline">
                    <div class="underline-data"></div>
                </div>
            </div>
        </div>
        
        <!-- SCHOLASTIC RECORD -->
        <div class="section-header">SCHOLASTIC RECORD</div>
        
        <div class="row">
            <!-- Display grades by section, two sections per row -->
            <?php 
            $counter = 0;
            foreach ($grades_by_section as $section_id => $grades): 
                $section_info = $sections_info[$section_id] ?? ['section_name' => 'Unknown', 'gradelevel_name' => 'Unknown'];
                $col_class = ($counter % 2 == 0) ? 'col-6' : 'col-6';
            ?>
                <div class="<?php echo $col_class; ?> grade-section">
                    <div class="row mb-2">
                        <div class="col-8">
                            <label>School:</label>
                            <div class="underline">
                                <div class="underline-data">EASTERN ACHIEVER ACADEMY OF TAGUIG INC.</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>School ID:</label>
                            <div class="underline">
                                <div class="underline-data"><?php 
                                echo isset($student['username']) 
                                    ? strtoupper(htmlspecialchars($student['username'])) 
                                    : ''; 
                                ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-4">
                            <label>District:</label>
                            <div class="underline">
                                <div class="underline-data">1</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Division:</label>
                            <div class="underline">
                                <div class="underline-data">TAGUIG CITY</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Region:</label>
                            <div class="underline">
                                <div class="underline-data">NCR</div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-3">
                            <label>Classified as Grade:</label>
                            <div class="underline">
                                <div class="underline-data"><?php echo htmlspecialchars($section_info['gradelevel_name']); ?></div>
                            </div>
                        </div>
                        <div class="col-3">
                            <label>Section:</label>
                            <div class="underline">
                                <div class="underline-data"><?php echo htmlspecialchars($section_info['section_name']); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <label>School Year:</label>
                            <div class="underline">
                                <div class="underline-data">2024-2025</div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    $total_final_rating = 0;
                    $subject_count = 0;
                    $total_quarter1 = 0;
                    $total_quarter2 = 0;
                    $total_quarter3 = 0;
                    $total_quarter4 = 0;
                    ?>

                    <!-- Grades Table -->
                    <table class="table table-bordered">
                        <tr>
                            <th rowspan="2" class="align-middle" style="width: 25%;">LEARNING AREAS</th>
                            <th colspan="4">Quarterly Rating</th>
                            <th rowspan="2" class="align-middle" style="width: 10%;">Final Rating</th>
                            <th rowspan="2" class="align-middle" style="width: 15%;">Remarks</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                        </tr>

                        <?php
                        foreach ($grades as $grade) {
                            $quarter1 = floatval($grade['quarter1']);
                            $quarter2 = floatval($grade['quarter2']);
                            $quarter3 = floatval($grade['quarter3']);
                            $quarter4 = floatval($grade['quarter4']);

                            $final_rating = round(($quarter1 + $quarter2 + $quarter3 + $quarter4) / 4);

                            $remarks = $final_rating >= 75 ? "PASSED" : "FAILED";

                            $total_final_rating += $final_rating;
                            $total_quarter1 += $quarter1;
                            $total_quarter2 += $quarter2;
                            $total_quarter3 += $quarter3;
                            $total_quarter4 += $quarter4;
                            $subject_count++;

                            echo "<tr>
                                    <td class='left-align'>" . htmlspecialchars($grade['subject_name']) . "</td>
                                    <td>" . round($quarter1) . "</td>
                                    <td>" . round($quarter2) . "</td>
                                    <td>" . round($quarter3) . "</td>
                                    <td>" . round($quarter4) . "</td>
                                    <td>$final_rating</td>
                                    <td>$remarks</td>
                                </tr>";
                        }

                        $general_quarter1 = $subject_count > 0 ? round($total_quarter1 / $subject_count) : 0;
                        $general_quarter2 = $subject_count > 0 ? round($total_quarter2 / $subject_count) : 0;
                        $general_quarter3 = $subject_count > 0 ? round($total_quarter3 / $subject_count) : 0;
                        $general_quarter4 = $subject_count > 0 ? round($total_quarter4 / $subject_count) : 0;

                        $general_average = $subject_count > 0 ? round($total_final_rating / $subject_count) : 0;

                        $general_remarks = $general_average >= 75 ? "PASSED" : "FAILED";
                        ?>

                        <!-- General Average Row -->
                        <tr>
                            <td class="left-align">General Average</td>
                            <td><?php echo $general_quarter1; ?></td>
                            <td><?php echo $general_quarter2; ?></td>
                            <td><?php echo $general_quarter3; ?></td>
                            <td><?php echo $general_quarter4; ?></td>
                            <td><?php echo $general_average; ?></td>
                            <td><?php echo $general_remarks; ?></td>
                        </tr>
                    </table>

                        <hr>
                    
                    <!-- Remedial Classes -->
                    <!-- <table class="table table-bordered mt-2">
                        <tr>
                            <th colspan="6">Remedial Classes</th>
                        </tr>
                        <tr>
                            <td colspan="2">Conducted from:</td>
                            <td colspan="2">to:</td>
                        </tr>
                        <tr>
                            <th style="width: 25%;">Learning Areas</th>
                            <th style="width: 15%;">Final Rating</th>
                            <th style="width: 15%;">Remedial Class Mark</th>
                            <th style="width: 15%;">Recomputed Final Grade</th>
                            <th style="width: 30%;">Remarks</th>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table> -->
                </div>
                
                <?php 
                $counter++;
                if ($counter % 2 == 0): 
                ?>
                    </div><div class="row">
                <?php endif; ?>
                
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>