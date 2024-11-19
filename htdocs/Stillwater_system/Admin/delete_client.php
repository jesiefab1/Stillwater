<?php
    // Include the database connection file
    include('../db_connection.php');

    session_start();

    $_SESSION['UserType'] = "Admin";
    // Check if the user_admin is logged in
    if (!isset($_SESSION['UserType'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'admin_login.php';</script>";
        exit;
    } 

    // Store the previous page URL in the session
    if (!isset($_SESSION['previous_page'])) {
        // Use HTTP_REFERER if available, otherwise fallback to 'Home.php'
        $_SESSION['previous_page'] = $_SERVER['HTTP_REFERRER'] ?? 'tableClient.php';
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
            var_dump($_SESSION);
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>