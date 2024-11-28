<?php
include('../../../db_connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $imageId = $_POST['id'];

    // Fetch the image path from the database
    $query = "SELECT filepath FROM Uploads WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $imageId);
    $stmt->execute();
    $result = $stmt->get_result();
    $imageRow = $result->fetch_assoc();

    if ($imageRow) {
        $filePath = '../../../Orderlist/' . $imageRow['filepath'];

        // Delete the image file from the filesystem
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the image record from the database
        $deleteQuery = "UPDATE Uploads SET Status = '1' WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("s", $imageId);
        $deleteStmt->execute();

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Image not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>