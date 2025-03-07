<?php
session_start();

include 'config.php';
$student_id = $_SESSION['student_id'];
if (!isset($student_id)) {
    header('location:../login.php');
    exit();
}

$sql_student = "
SELECT 
    u.username, 
    s.name, 
    s.gender, 
    s.student_house_number, 
    s.student_street, 
    s.student_barangay, 
    s.student_municipality, 
    s.guardian, 
    s.age,
    g.gradelevel_name,
    g.gradelevel_id
FROM users u
JOIN student s ON u.id = s.userid  
JOIN gradelevel g ON s.grade_level_id = g.gradelevel_id
WHERE s.userid = :user_id";

$stmt_student = $conn->prepare($sql_student);
$stmt_student->bindParam(':user_id', $student_id, PDO::PARAM_INT);
$stmt_student->execute();
$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "No user details found for the given student ID.";
    exit();
}

$sql_schedule = "
SELECT 
    sub.subject_name,
    sec.section_name,
    sch.start_time,
    sch.end_time,
    rm.room_name,
    sch.day
FROM schedules sch 
JOIN subjects sub ON sch.subject_id = sub.subject_id
JOIN sections sec ON sch.section_id = sec.section_id
JOIN rooms rm ON sch.room_id = rm.room_id
WHERE sch.grade_level = :grade_level_id";

$stmt_schedule = $conn->prepare($sql_schedule);
$stmt_schedule->bindParam(':grade_level_id', $student['gradelevel_id'], PDO::PARAM_INT);
$stmt_schedule->execute();
$schedules = $stmt_schedule->fetchAll(PDO::FETCH_ASSOC);

echo "<!-- Grade Level ID: " . $student['gradelevel_id'] . " -->";
echo "<!-- Number of schedules found: " . count($schedules) . " -->";

$stmt_schedule = $conn->prepare($sql_schedule);
$stmt_schedule->bindParam(':grade_level_id', $student['gradelevel_id'], PDO::PARAM_INT);
$stmt_schedule->execute();
$schedules = $stmt_schedule->fetchAll(PDO::FETCH_ASSOC); 

$sql_transactions = "
SELECT payment_method, created_at, balance 
FROM transactions 
WHERE user_id = :user_id 
ORDER BY created_at DESC 
LIMIT 1";

$stmt_transactions = $conn->prepare($sql_transactions);
$stmt_transactions->bindParam(':user_id', $student_id, PDO::PARAM_INT);
$stmt_transactions->execute();
$transaction = $stmt_transactions->fetch(PDO::FETCH_ASSOC);

$username = $student["username"] ?? "N/A";
$name = $student["name"] ?? "N/A";
$gradelevel_name = $student["gradelevel_name"] ?? "N/A";
$gradelevel_id = $student["gradelevel_id"] ?? 0;
$gender = $student["gender"] ?? "N/A";
$guardian = $student["guardian"] ?? "N/A";
$address = ($student["student_house_number"] ?? "") . ", " . 
           ($student["student_street"] ?? "") . ", " . 
           ($student["student_barangay"] ?? "") . ", " . 
           ($student["student_municipality"] ?? "");
$age = $student["age"] ?? "N/A";

$subject_name = $schedule["subject_name"] ?? "N/A";
$section_name = $schedule["section_name"] ?? "N/A";
$start_time = $schedule["start_time"] ?? "N/A";
$end_time = $schedule["end_time"] ?? "N/A";
$room_name = $schedule["room_name"] ?? "N/A";

if ($transaction) {
    $payment_method = $transaction["payment_method"] ?? "N/A";
    $created_at = $transaction["created_at"] ?? "N/A";
    $balance = $transaction["balance"] ?? "N/A";
} else {
    $payment_method = $created_at = $balance = "No transaction available";
}

$conn = null;
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
      max-width: 100px;
      /* adjust size as needed */
    }

    .signature {
      float: right;
      margin-left: 10px;
      max-width: 140px;
      /* adjust size as needed */
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

    th,
    td {
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
        <td>Student Number: <?php echo $username; ?></td>
        <td>School Year:</td>
        <td>2024-2025</td>
      </tr>
      <tr>
        <td>Name: <?php echo $name; ?></td>
        <td>Year Level: </td>
        <td><?php echo $gradelevel_name; ?></td>
      </tr>
      <tr>
        <td>Gender: <?php echo $gender; ?></td>
        <td>Guardian: </td>
        <td><?php echo $guardian; ?></td>
      </tr>
      <tr>
        <td>Address : <?php echo $address; ?></td>
        <td>Age:</td>
        <td> <?php echo $age; ?> years old</td>
      </tr>
    </table>
  </div>
  <div class="container">
    <p>Encoded Subject(s)<br>
      A.Y 2024-2025
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
      <?php if (!empty($schedules)): ?>
        <?php foreach ($schedules as $schedule): ?>
          <tr>
            <td><?php echo $schedule["subject_name"] ?? "N/A"; ?></td>
            <td><?php echo $gradelevel_name . " - " . ($schedule["section_name"] ?? "N/A"); ?></td>
            <td>
              <?php 
              if (isset($schedule["start_time"]) && isset($schedule["end_time"])) {
                  echo date("g:i A", strtotime($schedule["start_time"])) . ' - ' . 
                       date("g:i A", strtotime($schedule["end_time"]));
                  
                  if (isset($schedule["day"]) && !empty($schedule["day"])) {
                      echo " (" . $schedule["day"] . ")";
                  }
              } else {
                  echo "N/A";
              }
              ?>
            </td>
            <td><?php echo $schedule["room_name"] ?? "N/A"; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td>
            <?php 
            echo (in_array($student['gradelevel_id'], [7, 10]) ? "English" : "Math"); 
            ?>
          </td>
          <td><?php echo $gradelevel_name . " - " . ($student['gradelevel_id'] == 10 ? "Masiyahin" : "Section A"); ?></td>
          <td>8:00 AM - 9:00 AM (Monday)</td>
          <td>
            <?php 
            echo (in_array($student['gradelevel_id'], [4, 5]) ? "Room 105" : "Room 102"); 
            ?>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
    </table>
  </div>

  <div class="container">
    <p style="margin-left:20px;">Approved: Nicole Manila<br>
      Registrar
    </p>

  </div>

  <?php
  // Use the already retrieved gradelevel_id variable, not $row["gradelevel_id"]
  if ($gradelevel_id >= 1 && $gradelevel_id <= 6) {
    echo '
  <div class="container">
    <table>
      <tr>
        <td style="font-weight:bold;">TUITION</td>
        <td style="text-align: right;"></td>
      </tr>
      <tr></tr>
      <tr>
        <td>Miscellaneous Fees</td>
        <td style="text-align: right;">₱7,350.00</td>
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
        <td>Tuition June to March </td>
        <td style="text-align: right;">₱9,975.00</td>
      </tr>
      <tr>
        <td>TOTAL</td>
        <td style="text-align: right;">₱17,325.00</td>
      </tr>
    </table>
  </div>';
  } elseif ($gradelevel_id >= 7 && $gradelevel_id <= 10) {
    echo '
  <div class="container">
    <table>
      <tr>
        <td style="font-weight:bold;">TUITION</td>
        <td style="text-align: right;"></td>
      </tr>
      <tr></tr>
      <tr>
        <td>Miscellaneous Fees</td>
        <td style="text-align: right;">₱5,000.00</td>
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
        <td>Tuition June to March </td>
        <td style="text-align: right;">₱11,800.00</td>
      </tr>
      <tr>
        <td>TOTAL</td>
        <td style="text-align: right;">₱16,800.00</td>
      </tr>
    </table>
  </div>';
  } else {
    echo '<p>No tuition information available for this grade level.</p>';
  }
  ?>
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
          <td style="text-align: right;"><?php echo htmlspecialchars($payment_method); ?></td>
        </tr>
        <tr>
          <td>Date</td>
          <td style="text-align: right;">
            <?php
            if ($created_at && $created_at !== "N/A" && $created_at !== "No transaction available") {
              echo date("F j, Y", strtotime($created_at));
            } else {
              echo 'No transaction available';
            }
            ?>
          </td>
        </tr>
        <tr>
          <td>Balance</td>
          <td style="text-align: right;">
            <?php 
            if ($balance && $balance !== "N/A" && $balance !== "No transaction available") {
              echo "₱" . htmlspecialchars($balance);
            } else {
              echo 'No transaction available';
            }
            ?>
          </td>
        </tr>
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