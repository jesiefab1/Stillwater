<?php
    session_start();
    include '../db_connection.php';

    $client_id = "";

    if (isset($_SESSION['UserType'])) {
        $UserType = $_SESSION['UserType'];
    }

    // Get the Client_id from the session
    if (isset($_SESSION['Client_id'])) {
        $client_id = $_SESSION['Client_id'];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop Homepage - Start Bootstrap Template</title>
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
        <script>
            window.addEventListener("scroll", function(){
                var nav = document.querySelector("nav");
                nav.classList.toggle("bg-black", window.scrollY > 0);
            })
            function openOrder(Item_number, Client_id) {
                // Construct the URL with query parameters
                const url = `../Orderlist/order.php?Item_number=${Item_number}&Client_id=${Client_id}`;
                // Redirect to the order page
                window.location.href = url;
            }
        </script>
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg fixed-top container-fluid">
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
                            <div class="dropdown ms-3">
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
        <!-- Header-->
        <header>
            <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/Crafting%20elegance%20with%20precision..png?raw=true" class="align-self-center img-fluid w-100 h-75 card-img">
        </header>
        <!-- Section-->
        <section class="py-5" style="background-color: #d4d6d9;">
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-lg-5 justify-content-center">
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
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center"><button onclick="openOrder('<?php echo addslashes($row['Item_number']); ?>', '<?php echo addslashes($row['Client_id']); ?>')" class="btn btn-outline-dark mt-auto">More</button></div>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>

                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>