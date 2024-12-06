<?php
// Include database connection file
include('../../../db_connection.php');

session_start();

// Get the Client_id from the session
if (isset($_SESSION['Client_id'])) {
    $client_id = $_SESSION['Client_id'];
} else {
    header('Location: ../../Home/Home.php');
    exit();
}

// Initialize response array
$response = array('message' => '', 'alert_type' => '');

// Check if Item_number is set
if (isset($_POST['Item_number'])) {
    $Item_number = $_POST['Item_number'];

    // Fetch existing item details
    $query = "SELECT * FROM Item WHERE Item_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $Item_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        $response['message'] = "Item not found.";
        $response['alert_type'] = "alert-danger";
        echo json_encode($response);
        exit();
    }

    // Update item details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $item_name = $_POST['Item_name'];
        $condition = $_POST['Condition'];
        $asking_price = (float) $_POST['Asking_price']; // Ensure this is a float
        $description = $_POST['Description'];
        $Item_number = $_POST['Item_number']; // Ensure this is defined

        // Prepare the update statement
        $query = "UPDATE Item SET Item_name = ?, `Condition` = ?, Asking_price = ?, Item_description = ? WHERE Item_number = ?";
        $stmt = $conn->prepare($query);

        // Correct the types: 'ssds' for the first four variables and 's' for Item_number
        $stmt->bind_param("ssdss", $item_name, $condition, $asking_price, $description, $Item_number);

        if ($stmt->execute()) {
            $response['alert_type'] = 'alert-success';
            $response['message'] = 'Item updated successfully!';
        } else {
            $response['alert_type'] = 'alert-danger';
            $response['message'] = 'Failed to update item. Please try again.';
        }

        echo json_encode($response);
        exit();
    }
    // Handle file uploads
    if (isset($_FILES['images'])) {
        $uploadDir = '../../../Orderlist/';
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetFile = $uploadDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($tmpName, $targetFile)) {
                // Insert the file path into the database
                $insertQuery = "INSERT INTO Uploads (Item_number, filepath) VALUES (?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("ss", $Item_number, $fileName);
                $insertStmt->execute();
            } else {
                echo json_encode(['message' => 'Failed to upload image: ' . $fileName, 'alert_type' => 'alert-danger']);
                exit();
            }
        }
    }
} else {
    $response['message'] = "Invalid request.";
    $response['alert_type'] = "alert-danger";
    echo json_encode($response);
    exit();
}
