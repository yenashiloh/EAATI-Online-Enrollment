<?php
// Your database connection logic
include 'config.php';

// Start session and error reporting
session_start();
error_reporting(E_ALL);

// Check if the user is logged in
$teacher_id = $_SESSION['teacher_id'];
if (!isset($teacher_id)) {
    header('location:login.php');
    exit; // Stop further execution
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $encodedGradesId = $_POST['encodedGradesId'];
    $firstQuarter = $_POST['firstQuarter'];
    $secondQuarter = $_POST['secondQuarter'];
    $thirdQuarter = $_POST['thirdQuarter'];
    $fourthQuarter = $_POST['fourthQuarter'];

    // Prepare an update statement
    $sql = "UPDATE encodedgrades SET quarter1 = :firstQuarter, quarter2 = :secondQuarter, quarter3 = :thirdQuarter, quarter4 = :fourthQuarter WHERE encodedgrades_id = :encodedGradesId";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(':firstQuarter', $firstQuarter, PDO::PARAM_INT);
        $stmt->bindParam(':secondQuarter', $secondQuarter, PDO::PARAM_INT);
        $stmt->bindParam(':thirdQuarter', $thirdQuarter, PDO::PARAM_INT);
        $stmt->bindParam(':fourthQuarter', $fourthQuarter, PDO::PARAM_INT);
        $stmt->bindParam(':encodedGradesId', $encodedGradesId, PDO::PARAM_INT);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to the page where you display the table or any other appropriate page
            header("location: your_page.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        unset($stmt);
    }
}

// Close connection
unset($conn);
?>
