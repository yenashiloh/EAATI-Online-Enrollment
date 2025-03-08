<?php
include 'config.php';

session_start();

$student_id = $_SESSION['student_id'];

if (!isset($student_id)) {
    header('location:../login.php');
    exit;
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    $student_query = "SELECT * FROM student WHERE student_id = ?";
    $stmt = $conn->prepare($student_query);
    $stmt->bindParam(1, $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    $grade_levels_query = "SELECT gl.gradelevel_id, gl.gradelevel_name,
                          CAST(SUBSTRING(gl.gradelevel_name, 7) AS UNSIGNED) as grade_number
                          FROM encodedgrades eg
                          INNER JOIN schedules s ON eg.schedule_id = s.id
                          INNER JOIN sections sec ON s.section_id = sec.section_id
                          INNER JOIN gradelevel gl ON sec.gradelevel_id = gl.gradelevel_id
                          WHERE eg.student_id = ?
                          GROUP BY gl.gradelevel_id
                          ORDER BY grade_number DESC";
                          
    $stmt = $conn->prepare($grade_levels_query);
    $stmt->bindParam(1, $student_id, PDO::PARAM_INT);
    $stmt->execute();
    $grade_levels = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($grade_levels) === 0) {
        $latest_grade_id = null;
        $grade_level = 'Not assigned';
    } else {
        $latest_grade_id = $grade_levels[0]['gradelevel_id'];
        $grade_level = htmlspecialchars($grade_levels[0]['gradelevel_name']);
    }

    $grades_query = "SELECT eg.*, s.subject_name, s.section_id 
                     FROM encodedgrades eg 
                     INNER JOIN schedules s ON eg.schedule_id = s.id 
                     INNER JOIN sections sec ON s.section_id = sec.section_id
                     WHERE eg.student_id = ? AND sec.gradelevel_id = ?";
    $stmt = $conn->prepare($grades_query);
    $stmt->bindParam(1, $student_id, PDO::PARAM_INT);
    $stmt->bindParam(2, $latest_grade_id, PDO::PARAM_INT);
    $stmt->execute();
    $grades_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($grades_result) > 0) {
        $grade_row = $grades_result[0];
        $section_id = $grade_row['section_id'];
        $section_query = "SELECT sections.section_name
                         FROM sections 
                         WHERE sections.section_id = ?";
        $stmt = $conn->prepare($section_query);
        $stmt->bindParam(1, $section_id, PDO::PARAM_INT);
        $stmt->execute();
        $section = $stmt->fetch(PDO::FETCH_ASSOC);

        $section_name = htmlspecialchars($section['section_name'] ?? '');
    } else {
        $section_name = 'Not assigned';
    }
}
?>


<?php
if(isset($_GET['print']) && $_GET['print'] == 'true') {
    echo '<script>
        window.onload = function() {
            window.print();
        }
    </script>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Elementary School Report Card</title>
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
        $total_subjects = 0;
        $total_grade = 0;

        foreach ($grades_result as $grade) {
            $quarter1 = floatval($grade['quarter1']);
            $quarter2 = floatval($grade['quarter2']);
            $quarter3 = floatval($grade['quarter3']);
            $quarter4 = floatval($grade['quarter4']);

            $final_grade = round(($quarter1 + $quarter2 + $quarter3 + $quarter4) / 4);
            $total_grade += $final_grade;
            $total_subjects++;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                <td><?php echo round($quarter1); ?></td>
                <td><?php echo round($quarter2); ?></td>
                <td><?php echo round($quarter3); ?></td>
                <td><?php echo round($quarter4); ?></td>
                <td><?php echo round($final_grade); ?></td>
            </tr>
        <?php } ?>

        <?php
        $gwa = ($total_subjects > 0) ? round($total_grade / $total_subjects) : 0;
        ?>
        <tr>
            <td colspan="5"><strong>GENERAL WEIGHTED AVERAGE</strong></td>
            <td><?php echo round($gwa); ?></td>
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
