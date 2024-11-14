<?php
    // Start the session
    session_start();

    include ('db_connection.php');

    // Get the Client_id from the session
    if (isset($_SESSION['Client_id'])) {
        $client_id = $_SESSION['Client_id'];
    }

    $noLogin = !isset($_SESSION['Client_id']); // Check if the user is not logged in

    // Check if form is submitted
    if(isset($_POST['submit'])) {
        $Item_name = $_POST['Item_name'];
        $description = $_POST['description'];
        $asking_price = $_POST['asking_price'];
        $condition = $_POST['condition'];
        $is_sold = 0;
    
        // Prepare and bind
        $query = "INSERT INTO Item (Client_id, Item_name, Item_description, Asking_price, `Condition`, Is_sold) VALUES ('$client_id', '$Item_name', '$description', '$asking_price', '$condition', '$is_sold')";
        $result = mysqli_query($conn, $query);
    
        // Check if the query is executed
        if ($result) {
            echo "<script>alert('Item added successfully!');</script>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Menu</title>

    <script>
            // Set a JavaScript variable based on the PHP login status
        var isLoggedIn = <?php echo json_encode(!$noLogin); ?>; // true if logged in, false otherwise

        function logInRequired() {
            if (!isLoggedIn) {
                alert("You must be logged in to sell an Item"); // Alert the user
                window.location.href = 'log_in.php'; // Redirect to the login page
            }
        }
    </script>

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
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 95%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .client_id {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        
        button[type="submit"]:hover {
            background-color: #45a049;
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
                        <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/defaultAvatar.png?raw=true" class="avatar" alt="prof-picture">
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
            <li><a href="storage.php">My Items</a></li>
            <li><a href="sell.php" class="active">Sell</a></li>
            <li><a href="buy.php">Buy</a></li>
            <li><a href="aboutMe.php">About Me</a></li>
            <li><a href="Home.php">Home</a></li>
            <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="logo" alt="Comapny-Logo">
        </div>
    </ul>

    <div class="container">
        <h2>Sell an Item</h2>
        <form method="POST" action="">
            <label for="item_name">Item Name:</label>
            <input type="text" name="Item_name" required>
            
            <label for="description">Description:</label>
            <input type="text" name="description" required></input>
            
            <label for="asking_price">Asking Price:</label>
            <input type="number" name="asking_price" step="0.01" required>
            
            <label for="condition">Condition:</label>
            <input type="text" name="condition" required>
            
            <button type="submit" onclick="logInRequired()" name="submit">Sell Item</button>
        </form>
    </div>
</body>
</html>