<?php
    include ('db_connection.php');

    session_start();

    // Check if the user_admin is logged in
    if (!isset($_SESSION['user_admin'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'admin_login.php';</script>";
        exit;
    } 

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
        body {
            font-family: Arial, sans-serif;
            background-color: #fff8e1;
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
            padding: 10px 20px 10px 20px;
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
            background-color: #ffb921;
        }
        .deleteButton {
            background-color: #f44336;
        }
        .updateButton:hover {
            background-color: #ffa221;
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
        .add-button, .search-button {
            padding: 10px 20px;
            background-color: #ffb921;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .search-form input[type="text"], .search-form select {
            padding: 10px;
            margin-right: 10px;
        }
        .add-button:hover, .search-button:hover {
            background-color: #ffa221;
            transform: scale(1.05);
        }
        .highlight {
            background-color: yellow;
        }
    </style>
</head>
<body>
    <ul class="nav-menu">
        <li><a href="client.php">Client</a></li>
        <li><a href="item.php" class="active">Item</a></li>
        <li><a href="purchases.php">Purchases</a></li>
        <li><a href="sales.php">Sales</a></li>
        <li class="User"><a href="create_account.php">Client Side</a></li>
    </ul>

    <div style="text-align: right; margin: 20px;">
        <button onclick="window.location.href='add_item.php'" class="add-button">
            Add
        </button>
    </div>

    <!-- Search form -->
    <div style="text-align: center; margin: 20px;" class="search-form">
        <form method="GET" action="item.php">
            <input type="text" name="search" placeholder="Search...">
            <select name="status">
                <option value="">All</option>
                <option value="0">Available</option>
                <option value="1">Sold</option>
            </select>
            <button type="submit" class="search-button">
                Search
            </button>
        </form>
    </div>

    <table class="Display_table">
        <tr>
            <th>Client id</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Asking Price</th>
            <th>Condition</th>
            <th>Comments</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        // Get search parameters
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $status = isset($_GET['status']) ? $_GET['status'] : '';

        $query = "SELECT * FROM Item INNER JOIN Client ON Item.Client_id = Client.Client_id WHERE (Item_name LIKE '%$search%' OR Client.Client_id LIKE '%$search%' OR Item_description LIKE '%$search%' OR Asking_price LIKE '%$search%' OR `Condition` LIKE '%$search%')";

        if ($status !== '') {
            $query .= " AND Is_sold = '$status'";
        }

        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        while($row = mysqli_fetch_array($result)) {

        setlocale(LC_MONETARY, 'c', 'en-PH');
        ?>

        <tr class="outputs">
            <td><?php echo highlight($row['Client_id'], $search); ?></td>
            <td><?php echo highlight($row['Item_name'], $search); ?></td>
            <td><?php echo highlight($row['Item_description'], $search); ?></td>
            <td><?php echo highlight(number_format($row['Asking_price'], 2), $search); ?></td>
            <td><?php echo highlight($row['Condition'], $search); ?></td>
            <td><?php echo ($row['Comments']); ?></td>
            <td><?php echo $row['Is_sold'] == 0 ? 'Available' : 'Sold'; ?></td>
            <td>
                <div class="button-container">
                    <?php updateButton($row['Item_number']); ?>
                    <?php deleteButton($row['Item_number']); ?>
                </div>
            </td>
        </tr>

        <?php
        }
        ?>
    </table>
</body>
</html>
