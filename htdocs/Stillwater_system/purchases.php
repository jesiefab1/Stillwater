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
        <li><a href="purchases.php" class="active">Purchases</a></li>
        <li><a href="sales.php">Sales</a></li>
    </ul>

    <!-- Button to add a new client -->
    <div style="text-align: right; margin: 20px;">
        <button onclick="window.location.href='insert.php'" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease, transform 0.3s ease;">
        Add
        </button>
    </div>
    <script>
        // JavaScript to add hover effects to the add button
        document.querySelector('button').addEventListener('mouseover', function() {
            this.style.backgroundColor = '#45a049';
            this.style.transform = 'scale(1.05)';
        });
        document.querySelector('button').addEventListener('mouseout', function() {
            this.style.backgroundColor = '#4CAF50';
            this.style.transform = 'scale(1)';
        });
    </script>

    <!-- Table to display client data -->
    <table class="Display_table">
        <tr>
            <th>Item number</th>
            <th>Client id</th>
            <th>Purchase Cost</th>
            <th>Date Purchased</th>
            <th>Condition at Purchased</th>
            <th>Actions</th>
        </tr>
        <?php

        // Query to select all clients from the database
        $query = "SELECT * FROM Purchases
                INNER JOIN Item ON Purchases.Item_number = Item.Item_number 
                INNER JOIN Client ON Item.Client_id = Client.Client_id";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Loop through each row in the result set and display it in the table
        while($row = mysqli_fetch_array($result)) {
            
        ?>
        <tr class="outputs">
            <td><?php echo $row['Item_no']?></td>
            <td><?php echo $row['Client_id']; ?></td>
            <td><?php echo $row['Purchase_cost']; ?></td>
            <td><?php echo $row['Date_purchased']; ?></td>
            <td><?php echo $row['Condition_at_purchased']; ?></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>