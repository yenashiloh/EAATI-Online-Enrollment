<?php
include 'config1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sectionName = $_POST['sectionName'];
    $sectionDescription = $_POST['sectionDescription'];
    $sectionCapacity = $_POST['sectionCapacity'];

    // Prepare an insert statement
    $sql = "INSERT INTO sections (section_name, section_description, capacity) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $param_name, $param_description, $param_capacity);

        $param_name = $sectionName;
        $param_description = $sectionDescription;
        $param_capacity = $sectionCapacity;

        if (mysqli_stmt_execute($stmt)) {
            header("location: section.php?added=1");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($link);
?>
