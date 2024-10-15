<?php
    include ('db_connection.php');
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
        .nav-menu li {
            float: left;
        }
        .nav-menu .User {
            float: right;
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
        <li><a href="client.php">Client</a></li>
        <li><a href="item.php">Item</a></li>
        <li><a href="purchases.php">Purchases</a></li>
        <li><a href="sales.php" class="active">Sales</a></li>
        <li class="User"><a href="create_account.php">Client Side</a></li>
    </ul>

    <!-- Table to display client data -->
    <table class="Display_table">
        <tr>
            <th>Item Name</th>
            <th>Client id</th>
            <th>Commission Paid</th>
            <th>Selling Price</th>
            <th>Sales Tax</th>
            <th>Date Sold</th>
        </tr>
        <?php

        // Query to select all clients from the database
        $query = "SELECT * FROM Sales
                INNER JOIN Item ON Sales.Item_name = Item.Item_name 
                INNER JOIN Client ON Sales.Client_id = Client.Client_id";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Loop through each row in the result set and display it in the table
        while($row = mysqli_fetch_array($result)) {
            
        ?>
        <tr class="outputs">
            <td><?php echo $row['Item_name']?></td>
            <td><?php echo $row['Client_id']; ?></td>
            <td><?php echo $row['Commission_paid']; ?></td>
            <td><?php echo $row['Selling_price']; ?></td>
            <td><?php echo $row['Sales_tax']; ?></td>
            <td><?php echo $row['Date_sold']; ?></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>