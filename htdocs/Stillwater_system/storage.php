<?php
    // Start the session
    session_start();

    include ('db_connection.php');

    // Check if the user is logged in
    if (!isset($_SESSION['Client_id'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'log_in.php';</script>";
        exit;
    }

    // Get the Client_id from the session
    $client_id = $_SESSION['Client_id'];

    // Prepare the SQL query to fetch data for the specific client_id
    $query = "SELECT * FROM Item WHERE Client_id = '$client_id'";
    $result = mysqli_query($conn, $query);

    function updateButton($Item_number) {
        echo '<button onclick="window.location.href=\'update_item.php?Item_number=' . $Item_number . '\'" class="updateButton">
        Update
        </button>';
    }
    
    function deleteButton($Item_number) {
        echo '<button onclick="window.location.href=\'delete_item.php?Item_number=' . $Item_number . '\'" class="deleteButton">
        Delete
        </button>';
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Menu</title>
    <style>
        /* Basic styling for the body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        /* Styling for the navigation menu */
        .nav-menu {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: #333;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .nav-menu .User {
            float: right;
        }
        .nav-menu li {
            float: left;
        }
        .nav-menu li a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            display: block;
            transition: background-color 0.3s ease;
        }
        .nav-menu li a:hover {
            background-color: #575757;
        }
        .nav-menu li a.active {
            background-color: #4CAF50;
        }
        /* Styling for the card layout */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 40px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            width: 300px;
            box-sizing: border-box;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h3 {
            margin-top: 0;
            color: #333;
        }
        .card p {
            color: #555;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
        .updateButton, .deleteButton {
            padding: 10px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
        }
        .updateButton {
            background-color: #28a745;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .deleteButton {
            background-color: #dc3545;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .updateButton:hover {
            background-color: #218838;
        }
        .deleteButton:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <!-- Navigation menu -->
    <ul class="nav-menu">
        <li><a href="buy.php">Buy</a></li>
        <li><a href="sell.php">Sell</a></li>
        <li><a href="storage.php" class="active">Your Items</a></li>

        <!-- Temporary -->
        <li class="User"><a href="client.php">Administrator Side</a></li>
    </ul>

    <div class="card-container">
        <?php
            // Check if the query returned any rows
            if (mysqli_num_rows($result) > 0) {
                // Loop through each row in the result set and display it in the cards
                while ($row = mysqli_fetch_assoc($result)) {
                    setlocale(LC_MONETARY, 'c', 'en-PH');
                    ?>
                    <div class="card">
                        <h3><?php echo $row['Item_name']; ?></h3>
                        <p><strong>Description:</strong> <?php echo $row['Item_description']; ?></p>
                        <p><strong>Price:</strong> <?php echo number_format($row['Asking_price'], 2); ?></p>
                        <p><strong>Condition:</strong> <?php echo $row['Condition']; ?></p>
                        <p><strong>Comments:</strong> <?php echo $row['Comments']; ?></p>
                        <div class="button-container">
                            <?php updateButton($row['Item_number']); ?>
                            <?php deleteButton($row['Item_number']); ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No items found for this client.</p>";
            }
        ?>
    </div>

</body>
</html>
