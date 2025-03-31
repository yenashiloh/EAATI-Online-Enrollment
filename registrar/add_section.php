<?php
// Check if the 'added' or 'error' parameter is set and display messages
if (isset($_GET['added']) && $_GET['added'] == 1) {
    echo "<div class='alert alert-success'>New Section Added Successfully!</div>";
}
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<div class='alert alert-danger'>Section already exists for this grade level!</div>";
}

include 'config1.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sectionName = $_POST['sectionName'];
    $sectionDescription = $_POST['sectionDescription'];
    $sectionCapacity = $_POST['sectionCapacity'];
    $gradelevelId = $_POST['gradelevel_id'];

    // 🔍 Check if the section already exists in the same grade level
    $check_sql = "SELECT section_id FROM sections WHERE section_name = ? AND gradelevel_id = ?";
    if ($check_stmt = mysqli_prepare($link, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "si", $sectionName, $gradelevelId);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            // 🚫 Section already exists, redirect with error
            header("location: section.php?error=1");
            exit();
        }
        mysqli_stmt_close($check_stmt);
    }

    // ✅ Insert new section if it does not exist
    $sql = "INSERT INTO sections (section_name, section_description, sectionCapacity, gradelevel_id) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssii", $sectionName, $sectionDescription, $sectionCapacity, $gradelevelId);

        if (mysqli_stmt_execute($stmt)) {
            header("location: section.php?added=1"); // 🎉 Redirect with success message
            exit();
        } else {
            header("location: section.php?error=2"); // 🚨 Redirect with general error message
            exit();
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($link);
?>
