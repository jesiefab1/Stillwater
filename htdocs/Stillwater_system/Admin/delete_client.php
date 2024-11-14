<?php
    // Include the database connection file
    include ('db_connection.php');

    session_start();

    // Check if the user_admin is logged in
    if (!isset($_SESSION['user_admin'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'admin_login.php';</script>";
        exit;
    } 

    // Check if Client_id is set in the URL
    if (isset($_GET['Client_id'])) {
        $Client_id = $_GET['Client_id'];

        // Prepare the DELETE statement
        $stmt = $conn->prepare("UPDATE Client SET Status = 1 WHERE Client_id = ?");
        $stmt->bind_param("i", $Client_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the client page after deletion
            header("Location: client.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }
?>