<?php
require_once "config1.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $encodedgrades_id = intval($_POST['encodedgrades_id']);
    $status = $_POST['status'];

    if ($encodedgrades_id && in_array($status, ['Approved', 'Declined'])) {
        $sql = "UPDATE encodedgrades SET status = ? WHERE encodedgrades_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $status, $encodedgrades_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "success";
            } else {
                echo "error";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "error";
        }
    } else {
        echo "invalid";
    }
}

mysqli_close($link);
?>
