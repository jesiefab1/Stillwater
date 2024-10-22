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

    // Check if form is submitted
    if(isset($_POST['submit'])) {
        $Item_name = $_POST['Item_name'];
        $description = $_POST['description'];
        $asking_price = $_POST['asking_price'];
        $condition = $_POST['condition'];
        $is_sold = 0;
    
        // Prepare and bind
        $query = "INSERT INTO Item (Client_id, Item_name, Item_description, Asking_price, `Condition`, Is_sold) VALUES ('$client_id', '$Item_name', '$description', '$asking_price', '$condition', '$is_sold')";
        $result = mysqli_query($conn, $query);
    
        // Check if the query is executed
        if ($result) {
            echo "<script>alert('Item added successfully!');</script>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
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
        /* Styling for the table displaying client data */
        .Display_table {
            margin: auto;
            margin-top: 40px;
            margin-bottom: 40px;
            width: 80%;
            border-collapse: collapse;
        }
        .Display_table th, .Display_table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .Display_table th {
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }
        .outputs td {
            text-align: center;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 95%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .client_id {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <!-- Navigation menu -->
    <ul class="nav-menu">
        <li><a href="buy.php">Buy</a></li>
        <li><a href="sell.php" class="active">Sell</a></li>
        <li><a href="storage.php">Your Items</a></li>

        <!-- Temporary -->
        <li class="User"><a href="log_out.php">Administrator Side</a></li>
    </ul>
    <div class="logout-container">
        <button onclick="window.location.href='log_out.php'" class="logout">Logout</button>
    </div>

    <div class="container">
        <h2>Sell an Item</h2>
        <form method="POST" action="">
            <label for="item_name">Item Name:</label>
            <input type="text" name="Item_name" required>
            
            <label for="description">Description:</label>
            <input type="text" name="description" required></input>
            
            <label for="asking_price">Asking Price:</label>
            <input type="number" name="asking_price" step="0.01" required>
            
            <label for="condition">Condition:</label>
            <input type="text" name="condition" required>
            
            <input type="submit" name="submit" value="Sell Item">
        </form>
    </div>

</body>
</html>