<?php
// Get the ID from the URL
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Database connection parameters
$servername = "localhost"; // Your MySQL server
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "enrollment"; // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL statement with a placeholder for the ID
$sql = "SELECT * FROM generatedcor 
INNER JOIN student ON generatedcor.userId = student.userId 
INNER JOIN gradelevel on student.grade_level = gradelevel.gradelevel_id
INNER JOIN transactions on student.student_id = transactions.user_id
INNER JOIN payments on student.grade_level = payments.grade_level
WHERE generatedcor.id = ? AND status = 1 ";

// Prepare and bind parameter
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id); // Assuming ID is a string

// Execute query
$stmt->execute();

// Get result
$result = $stmt->get_result();

// Check if result has rows
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // Display student information
        $total = $row["total_whole_year"];
        $type=$row["installment_type"];
        $studentId=$row["student_id"];
        $student_name = $row["name"];
        $student_age = $row["age"];
        $student_address = $row["address"];
        $student_guardian = $row["guardian"];
        $grade = $row["gradelevel_name"];
        $date = $row["created_at"];
        $partialP = $row["partial_upon"];
        $uponP = $row["upon_enrollment"];
        $paymentA = $row["payment_amount"];
        $tuition = $total - $paymentA;
        $formatted_date = date("jS \d\a\y \of F, Y", strtotime($date));

        $dateString = $row["generate_at"];
        $formattedDate = date("F j, Y", strtotime($dateString));
        // Add more fields as needed
    }
} else {
    // No student found with the provided ID
    $student_name = "N/A";
    $grade = "N/A";
}

// Prepare SQL statement to fetch data from encodedstudentsubjects table
$sql_subjects = "SELECT * FROM generatedcor 
INNER JOIN student ON generatedcor.userId = student.userId 
INNER JOIN gradelevel on student.grade_level = gradelevel.gradelevel_id
INNER JOIN transactions on student.student_id = transactions.user_id
INNER JOIN encodedstudentsubjects on generatedcor.userId = encodedstudentsubjects.student_id
INNER JOIN schedules on encodedstudentsubjects.schedule_id = schedules.id
INNER JOIN subjects on schedules.subject_id = subjects.subject_id
INNER JOIN rooms ON schedules.room_id = rooms.room_id
INNER JOIN sections ON schedules.section_id = sections.section_id
WHERE generatedcor.id = ? AND status = 1";

// Prepare and bind parameter
$stmt_subjects = $conn->prepare($sql_subjects);
$stmt_subjects->bind_param("s", $id); // Assuming ID is a string

// Execute query
$stmt_subjects->execute();

// Get result
$result_subjects = $stmt_subjects->get_result();

// Initialize an empty array to store subject data
$subjects_data = array();

// Check if result has rows
if ($result_subjects->num_rows > 0) {
    // Fetch data from each row
    while ($row_subject = $result_subjects->fetch_assoc()) {
      
        // Store subject data in the array
        $subject_title = $row_subject["subject_name"];
        $section = $row_subject["section_name"];
        $schedule = date("g:i A", strtotime($row_subject["start_time"])) . " - " . date("g:i A", strtotime($row_subject["end_time"]));
        $room = $row_subject["room_name"];
        
        // Add subject data to the array
        $subjects_data[] = array($subject_title, $section, $schedule, $room);
    }
}

// Close statement and database connection
$stmt_subjects->close();
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Certification of Registration</title>
<style>
  body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
  }
  .container {
    max-width: 600px;
    margin: 0 auto;
    padding: 5px;
  }
  .containerOne {
    max-width: 600px;
    margin: 0 auto;
    padding: 5px;
    text-align: center;
  }
  .address {
    text-align: center;
    margin-bottom: 20px;
  }
  .certification {
    text-indent: 80px;
    text-align: justify;
  }
  .logo {
    float: left;
    margin-left: -50px;
    margin-right: 10px;
    max-width: 100px; /* adjust size as needed */
  }
  .signature {
    float: right;
    margin-left: 10px;
    max-width: 140px; /* adjust size as needed */
  }
   /* Style for print button container */
   .print-button-container {
    position: absolute;
    top: 20px;
    left: 20px;
  }
  /* Style for the print button */
  .print-button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
  }
  .print-button:hover {
    background-color: #0056b3;
  }
  /* Hide print button when printing */
  @media print {
    .print-button-container {
      display: none;
    }
  }
  table.center {
  margin-left: auto; 
  margin-right: auto;
}
  /* Style for the table */
  table {
    border-collapse: collapse;
    width: 100%;
  }
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
  }
  th {
    background-color: #f2f2f2;
  }
  .last-table {
    margin-top: 20px;
  }
</style>
</head>
<body>
  <div class="container">
    <center>
    <img src="../images/logo.png" alt="Logo" class="logo">
    <h3>EASTERN ACHIEVER ACADEMY OF TAGUIG INC.</h3>
    <h4>Blk 2 L-23 Ph.1 Pinagsama Village Taguig City</h4>
    </center>
  </div>

  <div class="container">
    <center>
    <h4>STUDENT INFORMATION</h4>
  </center>
  <table class="center">
  <tr>
    <td>Student Number: 000<?php echo $studentId; ?></td>
    <td>Section: <?php echo $section; ?></td>
    <td>Year: 2024-2025</td>
  </tr>
  <tr>
    <td>Name: <?php echo $student_name; ?></td>
    <td>Year Level: <?php echo $grade; ?></td>
    <td>Semester</td>
  </tr>
  <tr>
    <td>Gender</td>
    <td>Guardian: <?php echo $student_guardian; ?></td>
    <td></td>
  </tr>
  <tr>
    <td>Address : <?php echo $student_address; ?></td>
    <td>Age: <?php echo $student_age; ?></td>
    <td></td>
  </tr>
</table>
  </div>
  <div class="container">
  <p>Encoded Subject(s)<br>
A.Y 2023-2024
</p>
  </div>

  <div class="container">
    <table class="center">
        <thead>
            <tr>
                <th>Subject Title</th>
                <th>Section</th>
                <th>Schedule</th>
                <th>Room</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through subjects data and generate table rows
            foreach ($subjects_data as $subject_row) {
                echo "<tr>";
                foreach ($subject_row as $subject_info) {
                    echo "<td>$subject_info</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<div class="container">
  <p>Approved: Nicole Manila<br>
Registrar
</p>
  </div>
  <div class="container">
  <table>
  <tr>
    <td>Tuition</td>
    <td style="text-align: right;"><?php echo $total; ?></td>
  </tr>
  <tr>
    <?php
     if($type=='partial'){
      echo'<td>Partial Miscellaneous</td>';
     }else{
      echo'<td>Upon Enrollment Miscellaneous</td>';
     }
    ?>
    <?php
     if($type=='partial'){
      echo'<td style="text-align: right;">'.$partialP.'</td>';
     }else{
      echo'<td style="text-align: right;">'.$uponP.'</td>';
     }
    ?>
  </tr>
  <tr>
    <td>ID / TEST PAPER CARD</td>
    <td></td>
  </tr>
  <tr>
    <td>DEVELOPMENTAL</td>
    <td></td>
  </tr>
  <tr>
    <td>COMPUTER BASIC</td>
    <td></td>
  </tr>
  <tr>
    <td>LABORATORY/ LIBRARY</td>
    <td></td>
  </tr>
  <tr>
    <td>Tuition August to May</td>
    <?php
    echo '<td style="text-align: right;">'.$tuition.'</td>';
    ?>
  </tr>
  <tr>
    <td>TOTAL</td>
    <td style="text-align: right;"><?php echo $total; ?></td>
  </tr>
</table>
  </div>

  <div class="container">
    <table class="center last-table" colspan="2">
        <thead>
            <tr>
                <th colspan="2">TUITION FEE - PAYMENT DETAILS</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Payment mode</td>
                <?php
     if($type=='partial'){
      echo'<td>Partial</td>';
     }else{
      echo'<td>Upon Enrollment</td>';
     }
    ?>
            </tr>
            <tr>
                <td>Date</td>
                <?php
    echo '<td>'.$formattedDate.'</td>';
    ?>
            </tr>
            <tr>
                <td>Balance</td>
                <?php
    echo '<td>'.$tuition.'</td>';
    ?>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table>
  </div>

  <div class="container" style="text-align:center;">
     <p>___________________________________</p>
     <p>STUDENT SIGNATURE</p>
  </div>

  <div class="print-button-container">
    <button class="print-button" onclick="window.print()">Print</button>
  </div>

</body>
</html>
