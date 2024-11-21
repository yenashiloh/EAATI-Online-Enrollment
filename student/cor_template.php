<?php
  session_start();

  $user_id = $_SESSION['student_id'];

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "enrollment";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql_user_details = "
  SELECT 
      u.username,  -- Fetching the username from the users table
      s.name, 
      s.gender, 
      s.student_house_number, 
      s.student_street, 
      s.student_barangay, 
      s.student_municipality, 
      s.guardian, 
      s.age,
      g.gradelevel_name,
      sub.subject_name,
      sec.section_name,
      sec.gradelevel_id, 
      sch.start_time,
      sch.end_time,
      rm.room_name
  FROM users u
  JOIN student s ON u.id = s.userid  
  JOIN gradelevel g ON s.grade_level_id = g.gradelevel_id
  JOIN schedules sch ON sch.grade_level = g.gradelevel_id
  JOIN subjects sub ON sch.subject_id = sub.subject_id
  JOIN sections sec ON sch.section_id = sec.section_id
  JOIN rooms rm ON sch.room_id = rm.room_id
  WHERE s.userid = ?";

  $stmt_user_details = $conn->prepare($sql_user_details);
  $stmt_user_details->bind_param("i", $user_id);
  $stmt_user_details->execute();
  $result_user_details = $stmt_user_details->get_result();

  if ($result_user_details->num_rows > 0) {
    $row = $result_user_details->fetch_assoc();

    $username = $row["username"];  
    $name = $row["name"];
    $gradelevel_name = $row["gradelevel_name"];
    $gender = $row["gender"];
    $guardian = $row["guardian"];
    $address = $row["student_house_number"] . ", " . $row["student_street"] . ", " . $row["student_barangay"] . ", " . $row["student_municipality"];
    $age = $row["age"];
    $subject_name = $row["subject_name"];
    $section_name = $row["section_name"];
    $section_gradelevel_id = $row["gradelevel_id"];
    $start_time = $row["start_time"];
    $end_time = $row["end_time"];
    $room_name = $row["room_name"];
  } else {
    $username = $name = $gradelevel_name = $gender = $guardian = $address = $age = $subject_name = $section_name = $start_time = $end_time = $room_name = "No data available";
  }
  $stmt_user_details->close();

  $sql_transactions = "
  SELECT payment_method, created_at, balance 
  FROM transactions 
  WHERE user_id = ? 
  ORDER BY created_at DESC 
  LIMIT 1";

  $stmt_transactions = $conn->prepare($sql_transactions);
  $stmt_transactions->bind_param("i", $user_id);
  $stmt_transactions->execute();
  $result_transactions = $stmt_transactions->get_result();

  if ($result_transactions->num_rows > 0) {
    $transaction = $result_transactions->fetch_assoc();

    $payment_method = $transaction["payment_method"];
    $created_at = $transaction["created_at"];  
    $balance = $transaction["balance"];  
  } else {
    $payment_method = $created_at = $balance = "No transaction available";
  }
  $stmt_transactions->close();

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
        <tr>
          <td><?php echo $subject_name; ?></td>
          <td><?php echo $gradelevel_name . " - " . $section_name; ?></td>
          <?php
          date_default_timezone_set('Asia/Manila');
          ?>
          <td><?php echo date("g:i A", strtotime($start_time)) . ' - ' . date("g:i A", strtotime($end_time)); ?></td>
          <td><?php echo $room_name; ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="container">
    <p style="margin-left:20px;">Approved: Nicole Manila<br>
      Registrar
    </p>

  </div>

  <?php
  $gradelevel_id = $row["gradelevel_id"];

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
            if (!empty($transaction['created_at'])) {
              echo date("F j, Y", strtotime($transaction['created_at']));
            } else {
              echo 'No transaction available';
            }
            ?>
          </td>

        </tr>
        <tr>
          <td>Balance</td>
          <td style="text-align: right;">₱<?php echo htmlspecialchars($balance); ?></td>


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