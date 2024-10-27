<?php
    include ('db_connection.php');

    session_start();

    // Check if the user_admin is logged in
    if (!isset($_SESSION['user_admin'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'admin_login.php';</script>";
        exit;
    }  

    // Get search parameters
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $column = isset($_GET['column']) ? $_GET['column'] : '';

    // Query to select all columns from Sales and the Item_name from Item
    $query = "SELECT Sales.*, Item.Item_name FROM Sales 
              JOIN Item ON Sales.Item_number = Item.Item_number";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Check if any rows were returned
    if (mysqli_num_rows($result) == 0) {
        echo "No data found.";
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
            background-color: #fff8e1;
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

    <!-- Search form -->
    <div style="text-align: center; margin: 20px;">
        <form method="GET" action="sales.php">
            <input type="text" name="search" placeholder="Search...">
            <select name="column">
                <option value="Item_number">Item Name</option>
                <option value="Client_id">Client ID</option>
                <option value="Item_name">Commission Paid</option>
                <option value="Item_description">Selling Price</option>
                <option value="Asking_price">Sales Tax</option>
                <option value="Condition">Date Sold</option>
            </select>
            <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease, transform 0.3s ease;">
            Search
            </button>
        </form>
    </div>

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
        // Loop through each row in the result set and display it in the table
        while($row = mysqli_fetch_array($result)) {
                setlocale(LC_MONETARY, 'c', 'en-PH');
            
        ?>
        <tr class="outputs">
            <td><?php echo $row['Item_name'];?></td>
            <td><?php echo $row['Client_id']; ?></td>
            <td><?php echo number_format($row['Commission_paid']); ?></td>
            <td><?php echo number_format($row['Selling_price']); ?></td>
            <td><?php echo $row['Sales_tax']; ?></td>
            <td><?php echo $row['Date_sold']; ?></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>