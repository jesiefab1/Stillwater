<?php
    // Start the session
    session_start();

    include ('db_connection.php');


    // Get the Client_id from the session
    if (isset($_SESSION['Client_id'])) {
        $client_id = $_SESSION['Client_id'];
    }
    $noLogin = !isset($_SESSION['Client_id']); // Check if the user is not logged in
    
    if (isset($_SESSION['Client_id'])) {
        $client_name_query = "SELECT First_name, Lastname FROM Client WHERE Client_id = '$client_id'";
        $client_result = mysqli_query($conn, $client_name_query);
        $client_row = mysqli_fetch_assoc($client_result);
    }

    
    // Check if Client_id is set in the URL
    if (isset($_GET['Item_number'], $_GET['Client_id'])) {
        $Item_number = $_GET['Item_number'];
        $Client_id = $_GET['Client_id'];
        
        // Corrected SQL query to join the Item and Client tables
        $query = "SELECT Item.*, Client.First_name, Client.Lastname
                  FROM Item 
                  JOIN Client ON Item.Client_id = Client.Client_id 
                  WHERE Item_number = '$Item_number' AND Client.Client_id = '$Client_id'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
    }

    // Handle comment submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment']) && isset($_SESSION['Client_id'])) {
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $client_name = $client_row['First_name'] . ' ' . $client_row['Lastname'];
        $full_comment = $client_name . ': ' . $comment;
        $query = "UPDATE Item SET Comments = CONCAT(COALESCE(Comments, ''), '\n', '$full_comment') WHERE Item_number = '$Item_number'";
        mysqli_query($conn, $query);
            
        // Refresh the page to see the new comment
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        $noLogin = true;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>

    <script>
        function goBack() {
            window.history.back(); // Go back to the previous page in the browser history
        }
            // Set a JavaScript variable based on the PHP login status
        var isLoggedIn = <?php echo json_encode(!$noLogin); ?>; // true if logged in, false otherwise

        function handleCommentSubmission(event) {
            if (!isLoggedIn) {
                event.preventDefault(); // Prevent form submission
                alert("You must be logged in to submit a comment."); // Alert the user
                window.location.href = 'log_in.php'; // Redirect to the login page
            }
        }

        function logInRequired() {
            if (!isLoggedIn) {
                alert("You must be logged in to buy an Item"); // Alert the user
                window.location.href = 'log_in.php'; // Redirect to the login page
            }
        }
    </script>

    <style>

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .item-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            position: relative;
        }
        .item-card h3 {
            margin-top: 0;
            color: #333;
        }
        .item-card p {
            margin: 10px 0;
            color: #555;
        }
        .comment-form {
            margin-top: 20px;
        }
        .comment-form textarea {
            width: 96%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        .comment-form button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .comment-form button:hover {
            background-color: #218838;
        }
        .buy-button-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .buy-button-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .buy-button-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="item-card">
    <div class="buy-button-container">
        <form method="POST">
            <button type="button" onclick="logInRequired()" name="buy">Buy</button>
        </form>
    </div>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy']) && isset($_SESSION['Client_id'])) {
        // Calculate the commission
        $commission_rate = .4;
        $commission = $row['Asking_price'] * $commission_rate / 100;
        setlocale(LC_MONETARY, 'c', 'en-PH');
    
        $commission = number_format($commission, 2);

        $Sales_tax = 0.12;

        // Get the current date and time
        $date_time = date('Y-m-d H:i:s');
    
        // Prepare and bind
        $query1 = "INSERT INTO Sales (Item_number, Client_id, Commission_paid, Selling_price, Sales_tax, Date_sold) VALUES ('$Item_number', '$Client_id', '$commission', '{$row['Asking_price']}', '$Sales_tax', '$date_time')";
        $result1 = mysqli_query($conn, $query1);

        $query2 = "INSERT INTO Purchases (Item_number, Client_id, Purchase_cost, Date_purchased, Condition_at_purchased) VALUES ('$Item_number', '$client_id', '{$row['Asking_price']}', '$date_time', '{$row['Condition']}')";
        $result2 = mysqli_query($conn, $query2);

        // Mark the item as sold
        $query3 = "UPDATE Item SET is_sold = 1 WHERE Item_number = '$Item_number'";
        $result3 = mysqli_query($conn, $query3);

        echo "<h3>Item purchased successfully!</h3>";

    }
    ?>
    <h3><?php echo htmlspecialchars($row['Item_name'] ?? ''); ?></h3>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($row['Item_description'] ?? ''); ?></p>
    <p><strong>Price:</strong> <?php echo number_format($row['Asking_price'] ?? 0, 2); ?></p>
    <p><strong>Condition:</strong> <?php echo htmlspecialchars($row['Condition'] ?? ''); ?></p>
    <p><strong>Comments:</strong> <?php echo nl2br(htmlspecialchars($row['Comments'] ?? '')); ?></p>

    <div class="comment-form">
        <form method="POST">
            <textarea name="comment" rows="4" placeholder="Add your comment here..."></textarea>
            <button type="submit" onclick="handleCommentSubmission(event)">Submit Comment</button>
        </form>
    </div>
</div>

</body>
</html>
