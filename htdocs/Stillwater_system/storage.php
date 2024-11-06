<?php
    // Start the session
    session_start();

    include ('db_connection.php');

    // Get the Client_id from the session
    if (isset($_SESSION['Client_id'])) {
        $client_id = $_SESSION['Client_id'];
    }

    // Prepare the SQL query to fetch data for the specific client_id
    if (isset($_SESSION['Client_id'])) {
        $query = "SELECT * FROM Item WHERE Client_id = '$client_id'";
        $result = mysqli_query($conn, $query);
    }

    function updateButton($Item_number) {
        echo '<button onclick="window.location.href=\'client_update_item.php?Item_number=' . $Item_number . '\'" class="updateButton">
        Update
        </button>';
    }
    
    function deleteButton($Item_number) {
        echo '<button onclick="window.location.href=\'client_delete_item.php?Item_number=' . $Item_number . '\'" class="deleteButton">
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
        .nav_wrapper {
            margin-left: 16%;
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
            float: right;
        }
        .nav-menu li a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            display: block;
            transition: background-color 0.3s ease;
            margin-top: 10px;
            margin-bottom: 10px;
            margin-right: 10px;
            font-weight: bold;
        }
        .nav-menu img {
            margin-bottom: 10px;
            margin-left: 10px;
            margin-right: 50px;
            z-index: 99999;
        }
        .nav-menu li a:hover {
            background-color: #575757;
        }
        .nav-menu li a.active {
            background-color: #4CAF50;
        }
        #logo {
            width: 15%;
            height: 13%;
            padding: 5px 5px 5px 9px;
            position: absolute;
            top: 10px;
            left: 3.5%;
            box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.1);
            background-color: #424242;
        }
        .avatar {
            width: 40px;
            height: 40px;
            margin-top: 13px;
        }
        .contents {
            position: absolute;
            top: 67px;
            right: 3.5%;
            display: none;
            min-width: 100px;
            background-color: #575757;
            transition: background-color .8s;
        }
        .dropDown .contents a {
            background-color: #575757;
            padding-left: 10px;
            margin-left: 10px;
        }
        .dropDown .contents a:hover {
            background-color: #979797;
        }
        .dropDown img {
            cursor: pointer;
        }
        .dropDown:hover .contents {
            display: inline-block;
            width: 150px;
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
<ul class="nav-menu">
        <div class="nav_wrapper">
            <?php
                if (!isset($_SESSION['Client_id'])) {
            ?>
                <li>
                    <a href="log_in.php">Login</a>
                </li>
            <?php
                } else {
            ?>
                <li>
                    <div class="dropDown">
                        <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/defaultAvatar.png?raw=true" class="avatar" alt="prof-pic">
                        <div class="contents">
                            <a href="profile.php">Profile</a>
                            <a href="admin_login.php">Administrator</a>
                            <a href="log_out.php">Logout</a>
                        </div>
                    </div>
                </li>
            <?php
                }
            ?>
            <li><a href="storage.php" class="active">My Items</a></li>
            <li><a href="sell.php">Sell</a></li>
            <li><a href="buy.php">Buy</a></li>
            <li><a href="aboutMe.php">About Me</a></li>
            <li><a href="Home.php">Home</a></li>
            <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="logo" alt="Company-Logo">
        </div>
    </ul>

    <div class="card-container">
        <?php
            if (isset($_SESSION['Client_id'])) {
                // Check if the query returned any rows
                if (mysqli_num_rows($result) > 0) {
                    // Loop through each row in the result set and display it in the cards
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Calculate the commission
                        $commission_rate = .4;
                        $commission = $row['Asking_price'] * $commission_rate / 100;
                        setlocale(LC_MONETARY, 'c', 'en-PH');
                        ?>
                        <div class="card">
                            <h3><?php echo $row['Item_name']; ?></h3>
                            <p><strong>Description:</strong> <?php echo $row['Item_description']; ?></p>
                            <p><strong>Price:</strong> <?php echo number_format($row['Asking_price'], 2)?></p>
                            <p><strong>Tax Sales:</strong> <?php echo number_format($commission, 2); ?></p>
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
                    echo "<p>You have no Items.</p>";
                }
            } else {
                echo "You must Login in order to have Items";
            }
        ?>
    </div>

</body>
</html>
