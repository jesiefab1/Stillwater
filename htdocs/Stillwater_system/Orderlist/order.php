<?php

    // Start the session
    session_start();

    include ('../db_connection.php');

    // Get the Client_id from the session
    if (isset($_SESSION['Client_id'])) {
        $client_id = $_SESSION['Client_id'];
    } else {
        $noLogin = !isset($_SESSION['Client_id']); // Check if the user is not logged in
    }
    
    if (isset($_SESSION['Client_id'])) {
        $client_name_query = "SELECT First_name, Lastname, Email FROM Client WHERE Client_id = '$client_id'";
        $client_result = mysqli_query($conn, $client_name_query);
        $client_row = mysqli_fetch_assoc($client_result);
    }
    
    // Check if Client_id is set in the URL
    if (isset($_GET['Item_number'], $_GET['Client_id'])) {
        $Item_number = $_GET['Item_number'];
        $Client_id = $_GET['Client_id'];
        
        // Corrected SQL query to join the Item and Client tables
        $query = "SELECT Item.*, Client.First_name, Client.Lastname
                  FROM Item 
                  JOIN Client ON Item.Client_id = Client.Client_id 
                  WHERE Item_number = '$Item_number' AND Client.Client_id = '$Client_id'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
    }

    $commentCondition = $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment']) && isset($_SESSION['Client_id']);

    // Handle comment submission
    if ($commentCondition) {     
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $client_name = (empty($client_row['First_name']) && empty($client_row['Lastname'])) 
        ? $client_row['Email']
        :  $client_row['First_name'] . ' ' . $client_row['Lastname'];
         
        $full_comment = $client_name . ': ' . $comment;
        $date = date('Y-m-d H:i:s');
        $query = "UPDATE Item 
        SET Comments = CONCAT(COALESCE(Comments, ''), '\n', '$full_comment'),
            Date_of_comments = '$date' 
            WHERE Item_number = '$Item_number'";
        mysqli_query($conn, $query);
        echo"<script>window.location.href='$_SERVER[REQUEST_URI]'</script>";
    } else {
        $noLogin = true;
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        function goBack() {
            window.history.back(); // Go back to the previous page in the browser history
        }
            // Set a JavaScript variable based on the PHP login status
            let isLoggedIn = <?php echo isset($_SESSION['Client_id']) ? 'true' : 'false'; ?>;

        function handleCommentSubmission(event) {
            if (!isLoggedIn) {
                event.preventDefault(); // Prevent form submission
                alert("You must be logged in to submit a comment."); // Alert the user
                window.location.href = 'log_in.php'; // Redirect to the login page
            }
        }

        function logInRequired() {
            if (!isLoggedIn) {
                alert("You must be logged in to buy an Item"); // Alert the user
                window.location.href = 'log_in.php'; // Redirect to the login page
                return false;
            } else {
                return true;
            }
        }
    </script>
</head>
<body>

        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg fixed-top container-fluid" style="background-color: #3b1704;">
            <div class="container px-4 px-lg-5">
                <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="companyLogo" class="img-fluid float-left" alt="Company Logo" style="width: 13%;">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                        <li class="nav-item"><a class="nav-link active text-white aria-current='page'" href="Home.php">Home</a></li>
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
<!-- Navbar -->

<!--Main layout-->
<main class="mt-5 pt-4">
    <div class="container mt-5">
        <!--Grid row-->
        <div class="row">
            <!--Grid column-->
            <div class="col-md-6 mb-4">
                <img src="https://mdbootstrap.com/img/Photos/Horizontal/E-commerce/Products/14.jpg" class="img-fluid" alt="" />
            </div>
            <!--Grid column-->

            <!--Grid column-->
            <div class="col-md-6 mb-4">
                <!--Content-->
                <div class="p-4">
                    <div class="mb-3">
                        <a href="">
                            <span class="badge bg-dark me-1">Category 2</span>
                        </a>
                        <a href="">
                            <span class="badge bg-info me-1">New</span>
                        </a>
                        <a href="">
                            <span class="badge bg-danger me-1">Bestseller</span>
                        </a>
                    </div>

                    <p class="lead">
                        <span class="me-1">
                            <del>$200</del>
                        </span>
                        <span>$100</span>
                    </p>

                    <strong><p style="font-size: 20px;">Description</p></strong>

                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Et dolor suscipit libero eos atque quia ipsa sint voluptatibus! Beatae sit assumenda asperiores iure at maxime atque repellendus maiores quia sapiente.</p>

                    <form class="d-flex justify-content-left">
                        <!-- Default input -->
                        <button class="btn btn-primary ms-1" type="submit">
                            Add to cart
                            <i class="fas fa-shopping-cart ms-1"></i>
                        </button>
                    </form>
                </div>
                <!--Content-->
            </div>
            <!--Grid column-->
        </div>
        <!--Grid row-->


</body>
</html>



