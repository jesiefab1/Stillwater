<?php
session_start();
include '../db_connection.php'; // Ensure this file contains your database connection logic

// Check if the user is logged in
if (!isset($_SESSION['Client_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to place a bid.']);
    exit;
}

// Initialize response array
$response = array('message' => '', 'alert_type' => '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the keys exist in the POST array
    if (isset($_POST['Bid_amount']) && isset($_POST['Item_number'])) {
        $bidAmount = $_POST['Bid_amount'];
        $itemNumber = $_POST['Item_number'];
        $askingPrice = $_POST['Asking_price'];

        // Get the client ID from the session
        $client_id = $_SESSION['Client_id'];

        // Validate the bid amount and item ID
        $bidAmount = floatval($bidAmount);
        $itemNumber = intval($itemNumber);
        $askingPrice = floatval($askingPrice);
        if ($bidAmount <= 0 || $itemNumber <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid bid amount or item ID.', 'alert_type' => 'alert-danger']);
            exit;
        }

        // Fetch the current highest bid for the item
        $stmt = $conn->prepare("SELECT MAX(Bid_amount) AS Highest_bid FROM Bids WHERE Item_number = ?");
        $stmt->bind_param("i", $itemNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $highestBid = $row['Highest_bid'] ?? 0; // Default to 0 if no bids exist

        // Check if the new bid is higher than the current highest bid
        if ($bidAmount < $highestBid || $bidAmount < $askingPrice) {
            echo json_encode(['success' => false, 'message' => 'Your bid must be higher than the current highest bid of $' . number_format($highestBid, 2) . '.', 'alert_type' => 'alert-danger']);
            exit;
        }


        // Place the new bid
        $stmt->close(); // Close the previous statement before preparing a new one
        $stmt = $conn->prepare("INSERT INTO Bids (Item_number, Client_id, Bid_stamp, Bid_amount,  First_asking_price) VALUES (?, ?, NOW(), ?, ?)");
        $stmt->bind_param("iidd", $itemNumber, $client_id, $bidAmount, $askingPrice); // Use "iis" for integer, integer, and string

        if ($stmt->execute()) {
            $response['alert_type'] = 'alert-success';
            $response['message'] = 'You have SUCCESSFULLY bidded!';
        } else {
            $response['alert_type'] = 'alert-danger';
            $response['message'] = 'You have FAILED to bid . Please try again.';
        }
        echo json_encode($response);
        exit();

        // Close the statement
        $stmt->close();
    } else {
        // Handle the case where the expected keys are not set
        echo json_encode(['success' => false, 'message' => 'Missing bid amount or item ID.', 'alert_type' => 'alert-danger']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.', 'alert_type' => 'alert-danger']);
    exit();
}

// Close the database connection
$conn->close();
