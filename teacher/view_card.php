<?php
// Include config file
require_once "config1.php";

echo '<style>
    body {
        font-size: 12px; /* Change this to your desired font size */
    }
    .container {
        display: flex;
    }
    .column1 {
        float: left;
        width: 50%;
      }
      .column2 {
        float: left;
        width: 33%;
      }
      
      /* Clear floats after the columns */
      .row:after {
        content: "";
        display: table;
        clear: both;
      }
    .column {
        flex: 50%;
        padding: 10px;
    }
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 12px;
    }
    td {
        text-align: center;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .header2 {
        
        margin-bottom: 10px;
    }
    .header1 {
        text-align: center;
        align-items: center;
        margin-bottom: 20px;
    }
    .header img {
        width: 100px; /* Adjust as needed */
        height: 100px; /* Adjust as needed */
    }
    .header h1 {
        text-align: center;
        margin: 0;
        padding: 0;
    }
    .student-details {
        margin-bottom: 5px;
        text-decoration: underline;
        text-decoration-thickness: 2px; 
    }
    .indent-par {
        text-indent: 80px; /* Adjust the value as needed */
    }
    .verticalTableHeader {
        text-align:center;
        white-space:nowrap;
        transform-origin:50% 50%;
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        transform: rotate(-90deg);
        
    }
    .verticalTableHeader:before {
        content:"";
        padding-top:100%;/* takes width as reference, + 10% for faking some extra padding */
        display:inline-block;
        vertical-align:middle;
    }
    
</style>';

// Check if student_id is set in the URL and is a valid integer
if(isset($_GET['id']) && ctype_digit($_GET['id'])){
    $student_id = $_GET['id'];
    
    // Prepare and execute SQL query to fetch student details
    $sql_student = "SELECT * FROM student INNER JOIN gradelevel on student.grade_level = gradelevel.gradelevel_id 
    WHERE student_id = ?";
    if($stmt_student = mysqli_prepare($link, $sql_student)){
        mysqli_stmt_bind_param($stmt_student, "i", $student_id);
        if(mysqli_stmt_execute($stmt_student)){
            $result_student = mysqli_stmt_get_result($stmt_student);
            $student = mysqli_fetch_assoc($result_student);
        } else {
            echo "Error executing the student query.";
        }
    } else {
        echo "Error preparing the student query.";
    }

    // Prepare and execute SQL query for the report card
    $sql = "SELECT * FROM student 
            INNER JOIN encodedgrades ON student.student_id = encodedgrades.student_id
            INNER JOIN gradelevel on student.grade_level = gradelevel.gradelevel_id
            INNER JOIN schedules ON encodedgrades.schedule_id = schedules.id
            INNER JOIN subjects ON schedules.subject_id = subjects.subject_id
            WHERE student.student_id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $student_id);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) > 0){
                // Initialize variables for computing the general weighted average
                $total_final_grade = 0;
                $num_subjects = 0;

                echo '
                
                <div class="container">
                    <div class="column">
                        <div class="header">
                            <img src="../images/logo.png" alt="Left Image">
                            <center><p><b>Republic of the Philippines<br>
                            DEPARTMENT OF EDUCATION<br>
                            National Capital Region<br>
                            DIVISION OF TAGUIG CITY AND PATEROS
                            </b></p></center>
                            <img src="../images/deped.png" alt="Right Image">
                        </div>
                        <div class="header1">
                            <center><p><b>EASTERN ACHIEVER ACADEMY OF TAGUIG INC.
                            </b></p></center>
                        </div>
                        <div class="header1">
                            <center><p><b>REPORT CARD
                            </b></p></center>
                        </div>
                        <div class="header2">
                            Name: <span class="student-details">'.$student['name'].'________________________</span><br>
                            LRN: <span class="student-details">________________________</span>
                            AGE: <span class="student-details">'.$student['age'].'_______________________</span><br>
                            Grade: <span class="student-details">'.$student['gradelevel_name'].'________________</span>
                            <br>
                            Section: <span class="student-details">'.$student['gradelevel_name'].'________________</span>
                            School Year: <span class="student-details">2024-2025__________</span>
                        </div>
                        
                        <div>
                            <p>Dear Parent:</p>
                            <p class="indent-par">This report card shows the ability and progress your child<br>
                            has made in the different learning areas as well as his/her core values.</p>
                            <p class="indent-par">The school welcomes you should you desire to know more<br>
                            about your childs progress.</p>
                        </div>
                        <div>
                            <span class="student-details">FE H. VERGARA</span>
                            <br>Principal
                        </div>
                        <div>
                            <center><p><b>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT
                            </b></p></center>
                        </div>
                        
                        <table style="width:100%">
                            <tr>
                                <th></th>
                                <td colspan="4">QUARTER</td>
                                <th></th>
                            </tr>
                            <tr>
                                <th>SUBJECTS</th>
                                <th>1</th>
                                <th>2</th>
                                <th>3</th>
                                <th>4</th>
                                <th>FINAL GRADE</th>
                            </tr>';
                            while($row = mysqli_fetch_assoc($result)){
                                $final_grade = intval(($row['quarter1'] + $row['quarter2'] + $row['quarter3'] + $row['quarter4']) / 4);
                                $total_final_grade += $final_grade; // Add the final grade to the total
                                $num_subjects++; // Increment the number of subjects
                                echo '<tr>
                                        <td>'.$row['subject_name'].'</td>
                                        <td>'.intval($row['quarter1']).'</td>
                                        <td>'.intval($row['quarter2']).'</td>
                                        <td>'.intval($row['quarter3']).'</td>
                                        <td>'.intval($row['quarter4']).'</td>
                                        <td>'.number_format($final_grade).'</td>
                                    </tr>';
                            }

                            // Calculate the general weighted average
                            $general_weighted_average = $total_final_grade / $num_subjects;

                            // Output the row for general weighted average
                            echo '<tr>
                                    <td colspan="5"><b>General Weighted Average</b></td>
                                    <td>'.number_format($general_weighted_average).'</td>
                                </tr>';

                            echo '</table>
                    </div>
                    <div class="column">
                        <div class="header1">
                        <center><p><b>REPORT ON LEARNERS OBSERVED VALUES 
                        </b></p></center>   
                        </div>
                        <table style="width: 100%;"> 
                        <thead> 
                        <tr> 
                            <th>Core Values</th> <!-- First column -->
                            <th>Behavior Statements</th> <!-- Second column -->
                            <th colspan="4">Quarter</th>
                        </tr> 
                    </thead> 
                    <tbody> 
                    <tr>
                    <td></td>
                    <td></td>
                    <th>1</th>
                    <th>2</th>
                    <th>3</th>
                    <th>4</th>
                    </tr>
                        <tr> 
                            <td rowspan="2">1. Maka-Diyos</td> <!-- First column -->
                            <td>Expresses one`s  spiritual beliefs while respecting beliefs of others</td> <!-- Second column -->
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr> 
                        <tr> 
                            <td>Shows adherence to ethical principles by upholding truth.</td> <!-- Second column -->
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr> 
                        <tr> 
                            <td rowspan="2">2. Makatao</td> <!-- First column -->
                            <td>Is sensitive to individual, social, and cultural differences</td> <!-- Second column -->
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr> 
                        <tr> 
                            <td>Demonstrates contributions toward solidarity.</td> <!-- Second column -->
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr> 
                        <tr> 
                            <td>3. Maka-kalikasan</td> <!-- First column -->
                            <td>Cares for the environment and utilizes resources wisely, judiciously and<br>economically</td> <!-- Second column -->
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr> 
                    
                        <tr> 
                            <td rowspan="2">4. Maka-Bansa </td> <!-- First column -->
                            <td>Demonstrates pride in being a filipino, exercises
                            the rights and
                            responsibilities of a filipino citizen</td> <!-- Second column -->
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr> 
                        <tr> 
                            <td>Demonstrates appropriate behavior in carrying out activities in the school, community, and country</td> <!-- Second column -->
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr> 
                    </tbody> 
</table>

<div class="row" style="text-align: center;">
  <div class="column1">
        <p><b>Marking</b><br>
        AO<br>
        SO<br>
        RO<br>
        NO</p>
  </div>
  <div class="column1"><p><b>Non-numerical rating</b><br>
  Always Observed<br>
        Sometimes Observed<br>
        Rarely Observed<br>
        Not Observed</p></div>
</div>

<div class="row" style="text-align: center;">
  <div class="column2">
        <p><b>Descriptors</b><br>
        Outstanding<br>
        Very Satisfactory<br>
        Satisfactory<br>
        Fairly Satisfactory<br>
        Did not Meet Expectations</p>
  </div>
  <div class="column2"><p><b>Grading Scale</b><br>
  90-100<br>
        85-89<br>
        80-84<br>
        75-79<br>
        Below 75</p></div>
        <div class="column2"><p><b>Remarks</b><br>
        Passed<br>
        Passed<br>
        Passed<br>
        Passed<br>
        Failed</p></div>     
</div>
<div class="row">
<div class="column1" style="text-align:center;">
<p><b>Parent/Guardian`s Signature</b></p>
<p>1<sup>st</sup> Quarter ______________________________</p>
<p>2<sup>nd</sup> Quarter ______________________________</p>
<p>3<sup>rd</sup> Quarter ______________________________</p>
<p>4<sup>th</sup> Quarter ______________________________</p>
</div>
<div class="column1">
<p><b>Promoted to grade:__________________________________</b></p>
<p><b>Eligible for admission to Grade:_______________________</b></p>
<p><b>Approved:</b></p>
<p style="margin-left: 80px;"><span class="student-details">FE H. VERGARA</span>
<br>Principal</p>
</div>
</div>

<table style="width:100%">
    <tr>
      <th></th>
      <th class="verticalTableHeader">August</th>
      <th class="verticalTableHeader">September</th>
      <th class="verticalTableHeader">October</th>
      <th class="verticalTableHeader">November</th>
      <th class="verticalTableHeader">December</th>
      <th class="verticalTableHeader">Januar</th>
      <th class="verticalTableHeader">February</th>
      <th class="verticalTableHeader">March</th>
      <th class="verticalTableHeader">April</th>
      <th class="verticalTableHeader">May</th>
      <th class="verticalTableHeader">June</th>
      <th class="verticalTableHeader">Total</th>
    </tr>
    <tr>
      <td>No. of<br>School Days</td>
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
      <td>No. of<br>Days Present</td>
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
      <td>No. of<br>Days Absent</td>
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
</table>

                        
                </div>';
            } else {
                echo "No records found for the student.";
            }
        } else {
            echo "Error executing the query.";
        }
    } else {
        echo "Error preparing the query.";
    }
} else {
    echo "Invalid request.";
}

// Close connection
mysqli_close($link);
?>
