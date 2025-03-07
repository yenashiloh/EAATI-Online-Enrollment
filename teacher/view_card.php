<?php
include 'config.php';
include 'config1.php';
session_start();

$teacher_id = $_SESSION['teacher_id'];

if (!isset($teacher_id)) {
    header('location:../login.php');
    exit;
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    $student_query = "SELECT * FROM student WHERE student_id = ?";
    $stmt = $link->prepare($student_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $student_result = $stmt->get_result();
    $student = $student_result->fetch_assoc();

    $grades_query = "SELECT eg.*, s.subject_name, s.section_id 
                     FROM encodedgrades eg 
                     INNER JOIN schedules s ON eg.schedule_id = s.id 
                     WHERE eg.student_id = ?";
    $stmt = $link->prepare($grades_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $grades_result = $stmt->get_result();

    if ($grades_result->num_rows > 0) {
        $grade_row = $grades_result->fetch_assoc();
        $section_id = $grade_row['section_id'];
        $section_query = "SELECT sections.section_name, gradelevel.gradelevel_name 
                         FROM sections 
                         INNER JOIN gradelevel ON sections.gradelevel_id = gradelevel.gradelevel_id 
                         WHERE sections.section_id = ?";
        $stmt = $link->prepare($section_query);
        $stmt->bind_param("i", $section_id);
        $stmt->execute();
        $section_result = $stmt->get_result();
        $section = $section_result->fetch_assoc();

        $section_name = htmlspecialchars($section['section_name'] ?? '');
        $grade_level = htmlspecialchars($section['gradelevel_name'] ?? 'Not assigned');
    } else {
        $section_name = 'Not assigned';
        $grade_level = 'Not assigned';
    }

    mysqli_data_seek($grades_result, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Elementary School Report Card</title>
    
    <style>
        .container{
             max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 2px solid #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .report-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            margin-top: 20px;
        }
        .core-values {
            margin-bottom: 20px;
        }
        .core-values h5 {
            font-weight: bold;
        }
        .marking-table {
            border-collapse: collapse;
            width: 55%; 
            margin-top: 10px;
        }
        .grading-scale {
            margin-bottom: 20px;
        }
        .grading-scale h5 {
            font-weight: bold;
        }
        .remarks {
            margin-bottom: 20px;
        }
        .remarks h5 {
            font-weight: bold;
        }
        .signature-section {
            margin-bottom: 20px;
        }
        .signature-section h5 {
            font-weight: bold;
        }
        .attendance-table {
            width: 100%;
        }
        .attendance-table th, .attendance-table td {
            border: 1px solid #000;
            text-align: center;
        }
        .attendance-table th {
            writing-mode: vertical-rl; 
            transform: rotate(180deg); 
            white-space: nowrap;
            padding: 10px;
            text-align: center;
        }

        .table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .core-table {
            border-collapse: collapse;
            width: 100%; 
        }

        .core-table th, .core-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .core-table thead th {
            text-align: center;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .report-card {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 2px solid #000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        u {
                text-decoration: none; 
            }
        .personal-info span {
            margin-left: 5px;
        }
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .text-content {
            text-align: center;
            flex: 1;
        }

        .logo-left, .logo-right {
            object-fit: contain;
        }

        .school-name {
            font-size: 1.2em;
            font-weight: bold;
        }
        .personal-info {
            margin-top: 20px;
            padding: 10px;
        }
        .personal-info div {
            margin-bottom: 5px;
        }
        .personal-info label {
            font-weight: bold;
            margin-right: 10px;
        }
        .personal-info input {
            border: none;
            background: transparent;
            width: 150px;
            text-align: left;
        }
        .grade-table {
            margin-top: 20px;
            text-align: center;
        }
        .grade-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .grade-table th, .grade-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .grade-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 10px;
        }
        .dear-parent {
            font-size: 0.9em;
            margin-top: 10px;
        }
        .principal-name {
            text-decoration: underline;
            margin-top: 40px;
            margin-bottom: -5px;
        }
        .principal {
            font-style: italic;
            margin-left: 37px;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
        .print-button {
            display: block;
            width:  70px;
     
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
           
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
               
                width: 100%;
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
            }
            .print-button {
                display: none;
            }
           
            #printableCore, #printableCore * {
                visibility: visible;
            }
                #printableCore {
                visibility: visible;
                position: relative;
                width: 100%;
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
                page-break-before: always; 
            }
        }
    </style>
</head>
<body>
    <div class="report-card" >
    <button onclick="window.print()" class="print-button">Print</button>
    <div id="printableArea">
        <div class="header">
        <div class="header-content">
            <img src="../asset/img/logo.png" alt="School Logo" class="logo-left" style=" width: 100px; height: 100px;">
            <div class="text-content">
                <div>Republic of the Philippines</div>
                <div>DEPARTMENT OF EDUCATION</div>
                <div>NATIONAL CAPITAL REGION</div>
                <div style="margin-bottom:10px;">DIVISION OF TAGUIG CITY AND PATERNOS</div>
                <div class="school-name" style="margin-bottom:10px;">EASTERN ACHIEVER ACADEMY OF TAGUIG, INC.</div>
            </div>
            <img src="../asset/img/DepEd-logo.webp" alt="DepEd Logo" class="logo-right" style=" width: 130px; height: 130px;">
        </div>
    </div>
    <div class="personal-info">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <label>Name:</label> 
                <u style="min-width: 300px; display: inline-block; border-bottom: 1px solid black; position: relative; padding-bottom: 5px;">
                    <span style="position: absolute; left: 0; bottom: 1px; width: 100%;"><?php echo htmlspecialchars($student['name']); ?></span>
                </u>
            </div>
            <div style="text-align: right;">
                <label>Age:</label> 
                <u style="min-width: 100px; display: inline-block; border-bottom: 1px solid black; position: relative; padding-bottom: 5px;">
                    <span style="position: absolute; left: 0; bottom: 1px; width: 100%;"><?php echo htmlspecialchars($student['age']); ?> years old</span>
                </u>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px;">
            <div>
                <label>Sex:</label> 
                <u style="min-width: 100px; display: inline-block; border-bottom: 1px solid black; position: relative; padding-bottom: 5px;">
                    <span style="position: absolute; left: 0; bottom: 1px; width: 100%;"><?php echo htmlspecialchars($student['gender']); ?></span>
                </u>
            </div>
            <div>
                <label>Grade:</label> 
                <u style="min-width: 100px; display: inline-block; border-bottom: 1px solid black; position: relative; padding-bottom: 5px;">
                    <span style="position: absolute; left: 0; bottom: 1px; width: 100%;"><?php echo $grade_level; ?></span>
                </u>
            </div>
            <div>
                <label>Section:</label> 
                <u style="min-width: 100px; display: inline-block; border-bottom: 1px solid black; position: relative; padding-bottom: 5px;">
                    <span style="position: absolute; left: 0; bottom: 1px; width: 100%;"><?php echo $section_name; ?></span>
                </u>
            </div>
        </div>
        <br>
        <div class="dear-parent">
                <label>Dear Parent:</label>
                <p style="margin-left: 15px;">This report card shows the ability and progress your child has made in the different learning areas as well as his/her core values. <br><br>The school welcomes you should you desire to know more about your child's progress.</p>
            </div>
        <div class="footer">
            <p class="principal-name">FE DE VERGARA</p>
            <p class="principal">Principal</p>
        </div>

        <div class="grade-table">
    <h4>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2">SUBJECTS</th>
                <th colspan="4">QUARTER</th>
                <th rowspan="2">FINAL GRADE</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($grade = $grades_result->fetch_assoc()) {
            // Calculate final grade (average of four quarters)
            $quarter1 = floatval($grade['quarter1']);
            $quarter2 = floatval($grade['quarter2']);
            $quarter3 = floatval($grade['quarter3']);
            $quarter4 = floatval($grade['quarter4']);
            
            $final_grade = round(($quarter1 + $quarter2 + $quarter3 + $quarter4) / 4, 2);
        ?>
            <tr>
                <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                <td><?php echo intval($grade['quarter1']) == $grade['quarter1'] ? intval($grade['quarter1']) : $grade['quarter1']; ?></td>
                <td><?php echo intval($grade['quarter2']) == $grade['quarter2'] ? intval($grade['quarter2']) : $grade['quarter2']; ?></td>
                <td><?php echo intval($grade['quarter3']) == $grade['quarter3'] ? intval($grade['quarter3']) : $grade['quarter3']; ?></td>
                <td><?php echo intval($grade['quarter4']) == $grade['quarter4'] ? intval($grade['quarter4']) : $grade['quarter4']; ?></td>

                <td><?php echo intval($final_grade) == $final_grade ? intval($final_grade) : $final_grade; ?></td>

            </tr>
        <?php } ?>
                    
           
            <?php

            $total_subjects = 0;
            $total_grade = 0;

            mysqli_data_seek($grades_result, 0);

            while($grade = $grades_result->fetch_assoc()) {
                $final_grade = round(
                    (floatval($grade['quarter1']) + 
                    floatval($grade['quarter2']) + 
                    floatval($grade['quarter3']) + 
                    floatval($grade['quarter4'])) / 4, 
                    2
                );
                
                $total_grade += $final_grade;
                $total_subjects++;
            }

            // Calculate General Weighted Average
            $gwa = ($total_subjects > 0) ? round($total_grade / $total_subjects, 2) : 0;
            ?>
            <tr>
                <td colspan="5"><strong>GENERAL WEIGHTED AVERAGE</strong></td>
                <td><?php echo intval($gwa) == $gwa ? intval($gwa) : $gwa; ?></td>

            </tr>
        </tbody>
    </table>
</div>
    </div>
    </div>
    </div>

    <div class="container">
    <div id="printableCore">
        <div class="report-header">
            <h4>REPORT ON LEARNER'S OBSERVED VALUES</h4>
        </div>
        <div class="table-container">
            <table class="core-table">
                <thead>
                    <tr>
                        <th rowspan="2">Core Values</th>
                        <th rowspan="2">Behavior Statements</th>
                        <th colspan="4" style="text-align: center; width: 50%;">Quarter</th>
                    </tr>
                    <tr>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1. Maka-Diyos</td>
                        <td> Expresses one's spiritual <br> beliefs while respecting <br> beliefs of others.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>2. Makatao </td>
                        <td>Shows adherence to <br> ethical principles by <br> upholding truth. <br><hr style="height: 1px; background-color: black; border: none;">Is sensitive to individual, <br> social, and cultural <br> differences.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>3. Maka-Kalikasan  </td>
                        <td>Cares for the environment <br> and utilizes resources <br> wisely, judiciously, and <br> economically.</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>4. Maka-Bansa</td>
                        <td>Demonstrates pride in <br> being a Filipino; exercises <br> the rights and <br> responsibilities of a <br> Filipino citizen. <br> <hr style="height: 1px; background-color: black; border: none;">Demonstrates appropriate <br> behavior in carrying out <br> activities in the school, <br> community, and country</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <table class="marking-table">
                <thead>
                    <tr>
                        <th>Marking</th>
                        <th>Non-numerical Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>AO</td>
                        <td>Always Observed</td>
                    </tr>
                    <tr>
                        <td>SO</td>
                        <td>Sometimes Observed</td>
                    </tr>
                    <tr>
                        <td>RO</td>
                        <td>Rarely Observed</td>
                    </tr>
                    <tr>
                        <td>NO</td>
                        <td>Not Observed</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>
        <div class="table-container">
            <table class="marking-table">
                <thead>
                    <tr>
                        <th>Descriptors</th>
                        <th>Grading Scale</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Outstanding</td>
                        <td>90-100</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Very Satisfactory </td>
                        <td>85-89</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Satisfactory</td>
                        <td>80-84</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Fairly Satisfactory </td>
                        <td>75-79</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Did Not Meet Expectations</td>
                        <td>Below 75</td>
                        <td>Failed</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h5 style="text-align: center;   font-weight: bold; margin-top: 40px;">Parent/Guardian's Signature</h5>
        <div style="text-align: center; margin-bottom: 10px;">1<sup>st</sup> Quarter ______________________________________________________</div>
        <div style="text-align: center; margin-bottom: 10px;">2<sup>nd</sup> Quarter ______________________________________________________</div>
        <div style="text-align: center; margin-bottom: 10px;">3<sup>rd</sup> Quarter ______________________________________________________</div>
        <div style="text-align: center; margin-bottom: 10px; ">4<sup>th</sup> Quarter ______________________________________________________</div>


        <div style="text-align: center; margin-bottom: 10px; font-weight: bold;">Promoted to grade: _______________________________________________</div>
        <div style="text-align: center; margin-bottom: 10px; font-weight: bold;">Eligible for Admission to Grade: ____________________________________</div>

        <div style="text-align: center; margin-bottom: 10px; font-weight: bold; margin-right: 490px;">Approved:</div>
        <div style="text-align: center; margin-right: 340px;  text-decoration: underline;">FE H. VERGARA</div>
        <div style="text-align: center; margin-bottom: 10px; margin-right: 340px;  ">Principal</div>

    <div class="table-container">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th></th>
                    <th>August</th>
                    <th>September</th>
                    <th>October</th>
                    <th>November</th>
                    <th>December</th>
                    <th>January</th>
                    <th>February</th>
                    <th>March</th>
                    <th>April</th>
                    <th>May</th>
                    <th>June</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: left;">No. of <br> School Days</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: left;">No. of <br> Days Present</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="text-align: left;">No. of <br> Days Absent</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                
            </tbody>
        </table>
    </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>
