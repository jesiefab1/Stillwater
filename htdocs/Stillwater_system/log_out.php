<?php
session_start();

include "db_connection.php";

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: Home.php");
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>
<body>
    <div class="logout-container">
        <script>
            alert("You have been logged out.");
        </script>
    </div>
</body>
</html>