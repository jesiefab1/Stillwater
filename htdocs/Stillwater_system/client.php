<?php
    include('db_connection.php');

    session_start();

    // Check if the user_admin is logged in
    if (!isset($_SESSION['user_admin'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'admin_login.php';</script>";
        exit;
    } 

    function updateButton($Client_id) {
        echo '<button onclick="window.location.href=\'update_client.php?Client_id=' . $Client_id . '\'" class="updateButton">
        Update
        </button>';
    }

    function deleteButton($Client_id) {
        echo '<button onclick="window.location.href=\'delete_client.php?Client_id=' . $Client_id . '\'" class="deleteButton">
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
        <li><a href="client.php" class="active">Client</a></li>
        <li><a href="item.php">Item</a></li>
        <li><a href="purchases.php">Purchases</a></li>
        <li><a href="sales.php">Sales</a></li>
        <li class="User"><a href="create_account.php">Client Side</a></li>
    </ul>

    <!-- Button to add a new client -->
    <div style="text-align: right; margin: 20px;">
        <button onclick="window.location.href='add_client.php'" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease, transform 0.3s ease;">
        Add
        </button>
    </div>

    <!-- Search form -->
    <div style="text-align: center; margin: 20px;">
        <form method="GET" action="client.php">
            <input type="text" name="search" placeholder="Search...">
            <select name="column">
                <option value="Client_id">Client ID</option>
                <option value="Lastname">Last Name</option>
                <option value="First_name">First Name</option>
                <option value="Phone_number">Phone Number</option>
                <option value="Email">Email</option>
                <option value="Address">Address</option>
            </select>
            <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease, transform 0.3s ease;">
            Search
            </button>
        </form>
    </div>

    <!-- Table to display client data -->
    <table class="Display_table">
        <tr>
            <th>Client id</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Email</th>
            <th>Address</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        // Get search parameters
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $column = isset($_GET['column']) ? $_GET['column'] : 'Lastname';

        // Query to select all clients from the database
        $query = "SELECT * FROM Client WHERE $column LIKE '%$search%' ORDER BY Lastname ASC";
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        // Loop through each row in the result set and display it in the table
        while($row = mysqli_fetch_array($result)) {
        ?>
        <tr class="outputs">
            <td><?php echo $row['Client_id']; ?></td>
            <td><?php echo $row['Lastname'] . ", " . $row['First_name']; ?></td>
            <td><?php echo $row['Phone_number']; ?></td>
            <td><?php echo $row['Email']; ?></td>
            <td><?php echo $row['Address']; ?></td>
            <td><?php echo $row['Status'] == 0 ? 'Valid' : 'Deleted'; ?></td>
            <td>
                <div class="button-container">
                    <?php updateButton($row['Client_id']); ?>
                    <?php deleteButton($row['Client_id']); ?>
                </div>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</body>
</html>