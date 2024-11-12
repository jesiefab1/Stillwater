<?php
session_start();

require 'libs/google-api-php-client/vendor/autoload.php';
require 'config.php';
require '../db_connection.php';

if (isset($_POST['id_token'])) {
    // Include the Google API Client Library
    require_once 'path/to/Google/autoload.php'; // Adjust the path as necessary

    $client = new Google_Client(['client_id' => CLIENT_ID]);
    $payload = $client->verifyIdToken($_POST['id_token']);

    if ($payload) {
        $userid = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'];

        // Check if user already exists in the database
        $stmt = $pdo->prepare("SELECT * FROM Client WHERE Client_id = ?");
        $stmt->execute([$userid]);
        $user = $stmt->fetch();

        if (!$user) {
            // Insert new user into the database
            $stmt = $pdo->prepare("INSERT INTO users (google_id, email, name) VALUES (?, ?, ?)");
            if (!$stmt->execute([$userid, $email, $name])) {
                echo json_encode(['status' => 'error', 'message' => 'Database insert failed']);
                exit;
            }
        }

        // Set session variables
        $_SESSION['user_id'] = $userid;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID token']);
    }
} else {
        header("Location: log_in.php");
    echo json_encode(['status' => 'no_token']);
}
?>