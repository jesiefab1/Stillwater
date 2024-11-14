<?php
    // Start the session
    session_start();

    include ('../../db_connection.php');

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
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->

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
<body style="background-color: #3b1704;">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg fixed-top container-fluid">
            <div class="container px-4 px-lg-5">
                <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="companyLogo" class="img-fluid float-left" alt="Company Logo" style="width: 13%;">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active text-white aria-current='page'" href="../../Home/Home.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link text-white-50" href="../Nav/aboutMe.php">About</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle nav-link text-white-50" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#!">All Products</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                                <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex mb-0">
                        <button type="button" class="btn btn-outline-light" type="submit">
                            <i class="bi-cart-fill me-1"></i>
                            Cart
                            <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                        </button>
                        <?php
                            if (!isset($_SESSION['Client_id'])) {
                        ?>
                            <ul class="navbar-nav me-auto ms-lg-4">
                                <li class="nav-item">
                                    <a href="../loginSystem/log_in.php" class="nav-link active text-white">Login</a>
                                </li>
                            </ul>
                        <?php
                            } else {  
                        ?>                      
                            <div class="dropdown">
                                <button class="btn btn-secondary rounded-circle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-person-circle fs-5"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="../Nav/profile.php">Profile</a></li>
                                    <li><a class="dropdown-item" href="../Nav/Dropdown/storage.php">My Items</a></li>
                                    <li><a class="dropdown-item" href="../loginSystem/log_out.php">Logout</a></li>
                                </ul>
                            </div>
                        <?php
                        }
                        ?>

                    </form>
                </div>
            </div>
        </nav>

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
                        <div class="col mb-5">
                            <div class="card h-100">
                                <!-- Product image-->
                                <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                                <!-- Product details-->
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <!-- Product name-->
                                        <h5 class="fw-bolder"><?php echo $row['Item_name']; ?></h5>
                                        <!-- Product price-->
                                        <?php echo '₱ ' . number_format($row['Asking_price'], 2); ?>
                                    </div>
                                </div>
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
