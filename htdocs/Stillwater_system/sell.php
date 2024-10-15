<?php
    include ('db_connection.php');

    // Check if form is submitted
    if(isset($_POST['submit'])) {
        $Item_name = $_POST['Item_name'];
        $Client_id = $_POST['Client_id'];
        $description = $_POST['description'];
        $asking_price = $_POST['asking_price'];
        $condition = $_POST['condition'];
    
        // Prepare and bind
        $query = "INSERT INTO Item (Client_id, Item_name, Item_description, Asking_price, `Condition`) VALUES ('$Client_id', '$Item_name', '$description', '$asking_price', '$condition')";
        $result = mysqli_query($conn, $query);
    
        // Check if the query is executed
        if ($result) {
            $success = true;
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
        /* Styling for the update and delete buttons */
        .updateButton, .deleteButton {
            padding: 10px 20px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-right: 5px; /* Add some space between the buttons */
        }
        .updateButton {
            background-color: #4CAF50;
        }
        .deleteButton {
            background-color: #f44336;
        }
        .updateButton:hover {
            background-color: #45a049;
        }
        .deleteButton:hover {
            background-color: #e53935;
        }
        /* Container for the buttons */
        .button-container {
            display: flex;
            justify-content: center;
            align-items: center;
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
        <li class="User"><a href="client.php">Administrator Side</a></li>
    </ul>

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