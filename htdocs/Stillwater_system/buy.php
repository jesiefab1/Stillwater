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

    function moreButton($Item_number, $Client_id) {
        echo '<button onclick="openOrderPopup(' . $Item_number . ', ' . $Client_id . ')" class="moreButton">
        More
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
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
        .logout-container {
            margin-right: 20px;
            text-align: right;
            padding: 10px;
        }
        .logout {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            background-color: #4CAF50;
        }
        .logout:hover {
            background-color: #45a049;
        }
        .item-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 40px auto;
            width: 80%;
        }
        .item-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 20px;
            width: calc(33.333% - 40px);
            box-sizing: border-box;
            transition: transform 0.3s ease;
        }
        .item-card:hover {
            transform: translateY(-5px);
        }
        .item-card h3 {
            margin-top: 0;
        }
        .item-card p {
            margin: 5px 0;
        }
        .moreButton {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            background-color: #4CAF50;
        }
        .moreButton:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function openOrderPopup(Item_number, Client_id) {
            var url = 'order.php?Item_number=' + Item_number + '&Client_id=' + Client_id;
            var width = 600;
            var height = 400;
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;
            window.open(url, 'OrderPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
        }
    </script>
</head>
<body>
    <ul class="nav-menu">
        <li><a href="buy.php" class="active">Buy</a></li>
        <li><a href="sell.php">Sell</a></li>
        <li><a href="storage.php">Your Items</a></li>
        <li class="User"><a href="log_out.php">Administrator Side</a></li>
    </ul>
    <div class="logout-container">
        <button onclick="window.location.href='log_out.php'" class="logout">Logout</button>
    </div>

    <div class="item-container">
        <?php
        $query = "SELECT * FROM Item WHERE Client_id != '$client_id'";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        while($row = mysqli_fetch_array($result)) {
        ?>

        <div class="item-card">
            <h3><?php echo $row['Item_name']; ?></h3>
            <p><strong>Description:</strong> <?php echo $row['Item_description']; ?></p>
            <p><strong>Price:</strong> <?php echo number_format($row['Asking_price'], 2); ?></p>
            <?php moreButton($row['Item_number'], $row['Client_id']); ?>
        </div>

        <?php
        }
        ?>
    </div>
</body>
</html>
