<?php
session_start();
include('../db_connection.php'); // Ensure this file contains the $conn variable

function validationAndDecodeIdToken($idToken) {
    $clientId = '175487461829-um8ubpj71oi097ug21komlb88f52qa5p.apps.googleusercontent.com'; // Your ClientID
    $googleApiUrl = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $idToken;

    $response = file_get_contents($googleApiUrl);
    $userInfo = json_decode($response, true);

    if (isset($userInfo['aud']) && $userInfo['aud'] === $clientId) {
        return $userInfo; // Returns user info if valid
    } else {
        return false; // Invalid token or client ID
    }
}
if (isset($_POST['id_token'])) {
    echo "True";
} else {
    echo "false";
}

if (isset($_POST['id_token'])) {
    $idToken = $_POST['id_token'];

    // Validate the Id token
    $userInfo = validationAndDecodeIdToken($idToken);
    $_SESSION["userInfo"] = $userInfo;


    if ($userInfo) {
        $email = $userInfo['email'];
        $password = $userInfo['password'];
        $_SESSION["ReceivedEmail"] = $email;

        // Check if the user already exists
        $checkQuery = "SELECT * FROM Client WHERE Email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            echo "User  already exists.";
        } else {
            // Insert user data into the database
            $stmt = $conn->prepare("INSERT INTO Client (Email) VALUES (?)");
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $checkStmt->close();
    } else {
        echo "Failed to decode ID token";
    }
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
    <p>hello</p>
</body>
</html>
