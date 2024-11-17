<?php
include('../db_connection.php');

    session_start();

    $_SESSION['UserType'] = "Admin";
    // Check if the user_admin is logged in
    if (!isset($_SESSION['UserType'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'admin_login.php';</script>";
        exit;
    } 

    function highlight($text, $search) {
        if ($search != '') {
            return str_ireplace($search, '<span class="highlight">' . $search . '</span>', $text);
        }
        return $text;
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
            background-color: #ffb921;
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
        .search-form {
            text-align: center;
            margin: 20px;
        }
        .search-form input[type="text"], .search-form select {
            padding: 10px;
            margin-right: 10px;
        }
        .search-button {
            padding: 10px 20px;
            background-color: #ffb921;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .search-button:hover {
            background-color: #ffa221;
            transform: scale(1.05);
        }
        .highlight {
            background-color: yellow;
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
        <li class="User"><a href="create_account.php">Client Side</a></li>
    </ul>

    <!-- Search form -->
    <div class="search-form">
        <form method="GET" action="purchases.php">
            <input type="text" name="search" placeholder="Search...">
            <select name="column">
                <option value="">All</option>
                <option value="Very Good">Very Good</option>
                <option value="Good">Good</option>
                <option value="Bad">Bad</option>
            </select>
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <!-- Table to display client data -->
    <table class="Display_table">
        <tr>
            <th>Item Name</th>
            <th>Client id</th>
            <th>Purchase Cost</th>
            <th>Date Purchased</th>
            <th>Condition at Purchased</th>
        </tr>
        <?php
        // Get search parameters
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $status = isset($_GET['column']) ? $_GET['column'] : '';

        // Query to select all clients from the database
        $query = "SELECT Purchases.*, Item.Item_name, Client.First_name, Client.Lastname FROM Purchases 
        INNER JOIN Item ON Purchases.Item_number = Item.Item_number
        INNER JOIN Client ON Purchases.Client_id = Client.Client_id 
        WHERE (Item_name LIKE '%$search%' OR Purchases.Client_id LIKE '%$search%' OR Purchase_cost LIKE '%$search%' OR Date_purchased LIKE '%$search%')";
        if ($status != '') {
            $query .= " AND Condition_at_purchased = '$status'";
        }
        $query .= " ORDER BY Date_purchased DESC";

        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Loop through each row in the result set and display it in the table
        while($row = mysqli_fetch_array($result)) {
                setlocale(LC_MONETARY, 'c', 'en-PH');            
            
        ?>
        <tr class="outputs">
            <td><?php echo highlight($row['Item_name'], $search)?></td>
            <td><?php echo highlight($row['Lastname'] . ', ' . $row['First_name'], $search); ?></td>
            <td><?php echo number_format($row['Purchase_cost']); ?></td>
            <td><?php echo $row['Date_purchased']; ?></td>
            <td><?php echo highlight($row['Condition_at_purchased'], $search); ?></td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>