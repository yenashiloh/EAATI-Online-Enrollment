<?php

include 'config1.php';

// Check if the ID parameter is provided
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $pdf_id = $_GET['id'];

    // Fetch the PDF content from the database based on the provided ID
    $query = "SELECT * FROM generatedcor WHERE id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param('i', $pdf_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the PDF with the provided ID exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Set the appropriate headers for PDF download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="certificate.pdf"');

        // Output the PDF content to the browser
        echo $row['cor_content'];
        exit();
    } else {
        // If PDF not found, redirect to a relevant page
        header('Location: index.php');
        exit();
    }
} else {
    // If ID parameter is missing, redirect to a relevant page
    header('Location: index.php');
    exit();
}

?>
