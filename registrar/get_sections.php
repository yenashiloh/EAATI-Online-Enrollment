<?php
include 'config.php';

// Get grade level from the request
$grade_level = isset($_GET['grade_level']) ? $_GET['grade_level'] : null;

if ($grade_level) {
    // Prepare query to get sections for the selected grade level
    $stmt = $conn->prepare("SELECT * FROM sections WHERE gradelevel_id = :grade_level ORDER BY section_name ASC");
    $stmt->bindParam(':grade_level', $grade_level, PDO::PARAM_INT);
    $stmt->execute();
    
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return JSON response with count and data
    header('Content-Type: application/json');
    echo json_encode([
        'count' => count($sections),
        'data' => $sections
    ]);
} else {
    // Return empty array if no grade level is provided
    header('Content-Type: application/json');
    echo json_encode([
        'count' => 0,
        'data' => []
    ]);
}
?>