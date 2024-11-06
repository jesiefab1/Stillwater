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
    <script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous">
    </script>
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
            margin-right: 15px;
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
        .avatar {
            width: 40px;
            height: 40px;
        }
        .poster {
            border: 1px solid #333;
            width: 80%;
            height: 300px;
            position: relative;
            left: 10%;
            top: 50px;
        }
        .contents {
            float: right;
            position: absolute;
            display: none;
            min-width: 100px;
            right: 0%;
            background-color: #575757;
            transition: background-color .8s;
        }
        .contents a {
            background-color: #575757;
        }
        .contents a:hover {
            background-color: white;
        }
        .dropDown img {
            cursor: pointer;
        }
        .dropDown:hover .contents {
            display: block;
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
                        <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/defaultAvatar.png?raw=true" class="avatar">
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
            <li><a href="buy.php">Buy</a></li>
            <li><a href="aboutMe.php">About Me</a></li>
            <li><a href="Home.php" class="active">Home</a></li>
        </div>
    </ul>

    <div class="poster">
        <>
    </div>

    <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="logo">

</body>
</html>