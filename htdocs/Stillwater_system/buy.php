<?php
    // Start the session
    session_start();

    include ('db_connection.php');
    
    // Get the Client_id from the session
    if (isset($_SESSION['Client_id'])) {
        $client_id = $_SESSION['Client_id'];
    }


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
            padding-left: 30px;
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
                        <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/defaultAvatar.png?raw=true" class="avatar" alt="prof-picture">
                            <div class="contents">
                                <a href="profile.php">Profile</a>
                                <a href="log_out.php">Logout</a>
                            </div>
                    </div>
                </li>
            <?php
                }
            ?>
            <li><a href="storage.php">My Items</a></li>
            <li><a href="sell.php">Sell</a></li>
            <li><a href="buy.php" class="active">Buy</a></li>
            <li><a href="aboutMe.php">About Me</a></li>
            <li><a href="Home.php">Home</a></li>
            <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="logo" alt="Company-Logo">
        </div>
    </ul>

    <div class="item-container">
        <?php
            if (isset($_SESSION['Client_id'])) {
                $query = "SELECT * FROM Item WHERE Client_id != '$client_id' AND Is_sold = '0'";
            } else {
                $query = "SELECT * FROM Item WHERE Is_sold = '0'";
            }

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
