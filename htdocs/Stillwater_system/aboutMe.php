<?php
    include ("db_connection.php");

    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Signika+Negative:wght@300..700&display=swap" rel="stylesheet">
    <title>Document</title>

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
            margin-top: 10px;
            margin-bottom: 10px;
            margin-right: 10px;
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
            left: 25px;
            box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.1);
            background-color: #424242;
        }
        .BG_wrapper {
            position: relative;
            width: auto;
            height: 500px;
        }
        .BG {
            position: absolute;
            right: 0px;
            height: 100%;
            width: 50%;
        }
        .BG img {
            width: 100%;
            height: 100%;
            opacity: .8;
        }
        .BG_color {
            position: absolute;
            background-color: #265c99;
            height: 100%;
            width: 100%;
            opacity: .3;
        }
        .Slogan {
            font-family: "Signika Negative", sans-serif;
            position: absolute;
            left: 5%;
            top: 30%;
            width: 50%;
        }
        .Slogan > p {
            font-size: 35px;
            font-weight: bold;
        }
        .desc {
            position: absolute;
            width: 40%;
            font-size: 20px;
            top: 50%;
            left: 6%;
        }
        .avatar {
            width: 40px;
            height: 40px;
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
                    <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/defaultAvatar.png?raw=true" class="avatar">
                </li>
            <?php
                }
            ?>
            <li><a href="storage.php">My Items</a></li>
            <li><a href="sell.php">Sell</a></li>
            <li><a href="buy.php">Buy</a></li>
            <li><a href="aboutMe.php" class="active">About Me</a></li>
            <li><a href="Home.php">Home</a></li>
        </div>
    </ul>


    <div class="BG_wrapper">
        <div class="BG_color"></div>
            <div class="Slogan">
                <p>Treasure From the Past Awaits</p>
            </div>
            <div class="desc"><span>Looking for antique furnitures? Here at Stillwater Antique ensures, you can get always get the furniture you've always wanted.</span></div>
        <div class="BG">
            <img src="https://www.relaxhouse.com.au/blog/wp-content/uploads/2023/12/TELEMMGLPICT000179595798_trans_NvBQzQNjv4Bqjj7NJ3YiIjAb1WHq3sE3uhKttfbwsG9PqG0T37pchKo-1024x640.jpeg">
        </div>
    </div>

        <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="logo">

</body>
</html>