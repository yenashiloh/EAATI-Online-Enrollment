<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    $gradeLevelID = $_POST['gradeLevelID'];
    $roomName = $_POST['roomName'];
    $roomDescription = $_POST['roomDescription'];

    // Prepare an insert statement
    $sql = "INSERT INTO rooms (gradelevel_id, room_name, room_description) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement
        $stmt->bindParam(1, $gradeLevelID, PDO::PARAM_INT);
        $stmt->bindParam(2, $roomName, PDO::PARAM_STR);
        $stmt->bindParam(3, $roomDescription, PDO::PARAM_STR);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect with success message
            header("location: room.php?added=1");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        $stmt->closeCursor();
    }

    // Close connection
    $conn = null;
}
?>
