<?php
    include ('db_connection.php');

    // Include the database connection file
    include('db_connection.php');

    // Start the session
    session_start();

    // Get the client_id from the session
    $client_id = $_SESSION['Client_id']; 

    // Check if Client_id is set in the URL
if (isset($_GET['Item_number'], $_GET['Client_id'])) {
    $Item_number = $_GET['Item_number'];
    $Client_id = $_GET['Client_id'];
    
    // Corrected SQL query to join the Item and Client tables
    $query = "SELECT Item.*, Client.First_name, Client.Lastname 
              FROM Item 
              JOIN Client ON Item.Client_id = Client.Client_id 
              WHERE Item.Item_number = '$Item_number' AND Client.Client_id = '$Client_id'";
    $result = mysqli_query($conn, $query);


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Menu</title>
    
    <style>
        body {
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .wrapper {
            width: 50%;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        span {
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 20px;
        }
        .Back {
        text-align: right;
        }
        .Back > button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 16px 8px;
            border: none;
            cursor: pointer;
            width: 100px;
            border-radius: 5px;
        }
        .vl {
            border-left: 2px solid #333;
            height: 90%;
            position: absolute;
            left: 40%;
            margin-left: -3px;
            top: 5%;
        }
        .commission-wrapper {
            position: absolute; left: 45%; top: 5%;

        }
    </style>
</head>
<body>
    <div class="Back">
        <button onclick="window.location.href='buy.php'">Back</button>
    </div>

    <div class="wrapper">
        <h2>Item Info:</h2>
        <?php
            // Check if the query returned any rows
            if (mysqli_num_rows($result) > 0) {
                // Fetch the result row
                $row = mysqli_fetch_assoc($result);

                setlocale(LC_MONETARY, 'c', 'en-PH');
                // Display the data (example)
        ?>
        <div class="item-info">
            <div class="item-field">
                <span class="label">Item Name:</span>
                <span class="value"><?php echo htmlspecialchars($row['Item_name']); ?></span>
            </div>
            <div class="item-field">
                <span class="label">Seller:</span>
                <span class="value"><?php echo htmlspecialchars($row['First_name'] . ' ' . $row['Lastname']); ?></span>
            </div>
            <div class="item-field">
                <span class="label">Description:</span>
                <span class="value"><?php echo htmlspecialchars($row['Item_description']); ?></span>
            </div>
            <div class="item-field">
                <span class="label">Asking Price:</span>
                <span class="value"><?php echo htmlspecialchars(number_format($row['Asking_price'], 2)); ?></span>
            </div>
            <div class="item-field">
                <span class="label">Condition:</span>
                <span class="value"><?php echo htmlspecialchars($row['Condition']); ?></span>
            </div>

            <div class="vl"></div>

            <div class="commission-wrapper">
                <label for="Commission">Commission:</label>
                <input type="number" name="Commission" required min="<?php echo htmlspecialchars($row['Asking_price']); ?>">
            </div>
        </div>
        <?php
            } else {
                echo "<p>No item found.</p>";
            }
        ?>
    </div>

</body>
</html>