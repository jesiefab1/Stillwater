<?php
// Include the database connection file
include('../../../db_connection.php');

session_start();

// Get the Client_id from the session
if (isset($_SESSION['Client_id'])) {
    $client_id = $_SESSION['Client_id'];
} else {
    header('Location: ../../Home/Home.php');
    exit();
}

// Check if the user_admin is logged in
if (!isset($_SESSION['UserType'])) {
    echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
    echo "<script>window.location.href = 'admin_login.php';</script>";
    exit;
} 

// Check if Client_id is set in the URL
if (isset($_GET['Item_number'])) {
    $Item_number = $_GET['Item_number'];

    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM Item WHERE Item_number = ?");
    $stmt->bind_param("i", $Item_number);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the client page after deletion
        header("Location: storage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>