<?php
$db_name = "mysql:host=localhost;dbname=enrollment";
$username = "root";
$password = "";

try {
    $GLOBALS['conn'] = new PDO($db_name, $username, $password);
    $GLOBALS['conn']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Database connection successful."); // Debugging
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage()); // Debugging
    die("Database connection failed: " . $e->getMessage());
}
?>