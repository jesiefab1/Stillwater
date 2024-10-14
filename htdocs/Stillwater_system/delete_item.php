<?php
// Include the database connection file
include ('db_connection.php');

// Check if Client_id is set in the URL
if (isset($_GET['Item_number'])) {
    $Item_number = $_GET['Item_number'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM Item WHERE Item_number = ?");
    $stmt->bind_param("i", $Item_number);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the client page after deletion
        header("Location: item.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>