<?php
session_start();
include('../db_connection.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        window.addEventListener("scroll", function() {
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
    <style>
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
            /* Smooth transition for effects */
        }

        .hover-card:hover {
            transform: scale(1.05);
            /* Slightly enlarge the card */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            /* Add shadow effect */
            border: 2px solid #007bff;
            /* Optional: add a border on hover */
        }
    </style>
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg fixed-top container-fluid">
        <div class="container px-4 px-lg-5">
            <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogoNew1.png?raw=true" id="companyLogo" class="img-fluid float-left" alt="Company Logo" style="width: 13%;">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active text-white aria-current='page'" href="Home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="../Nav/aboutMe.php">About</a></li>
                </ul>
                <form class="d-flex mb-0">
                    <ul class="navbar-nav me-auto ms-lg-4">
                        <li class="nav-item px-2 rounded">
                            <a href="../Orderlist/sell.php" class="btn btn-secondary rounded" role="button">
                                <i class="bi bi-cart4 fs-5"></i>
                            </a>
                        </li>
                    </ul>
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
                    // Get current date and time
                    $currentDateTime = date('Y-m-d H:i');
                    $query = "SELECT * FROM Item
                              WHERE Client_id != $client_id 
                              AND Is_sold = 0 
                              AND End_time > '$currentDateTime'";
                } else {
                    // Get current date and time
                    $currentDateTime = date('Y-m-d H:i');
                    $query = "SELECT * FROM Item 
                              WHERE Is_sold = 0
                              AND End_time > '$currentDateTime'";
                }

                $result = mysqli_query($conn, $query);

                if (!$result) {
                    die("Query failed: " . mysqli_error($conn));
                }

                if (mysqli_num_rows($result) == 0) {
                    echo "No items found.";
                }


                while ($row = mysqli_fetch_array($result)) {
                    // Initialize $imageSrc with a default image
                    $imageSrc = 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg'; // Default image

                    // Fetch the image for the current item
                    $item_number = $row['Item_number'];
                    $sqlImage = "SELECT * FROM Uploads WHERE Item_number = '$item_number'";
                    $queryImage = mysqli_query($conn, $sqlImage);

                    if (!$queryImage) {
                        echo "Error fetching images: " . mysqli_error($conn);
                        continue; // Skip this iteration if there's an error
                    }

                    // Fetch the highest bid from the Bids table
                    $highestBidQuery = "SELECT MAX(Bid_amount) as Highest_bid FROM Bids WHERE Item_number = ?";
                    $stmt = $conn->prepare($highestBidQuery);

                    if ($stmt === false) {
                        die("Error preparing the statement: " . $conn->error);
                    }

                    // Bind the parameter
                    $stmt->bind_param("s", $item_number);

                    // Execute the query
                    $stmt->execute();

                    // Get the result
                    $highestBidResult = $stmt->get_result();
                    $highestBidRow = $highestBidResult->fetch_assoc();

                    // Determine the highest bid or fallback to asking price
                    $highestBid = isset($highestBidRow['Highest_bid']) ? $highestBidRow['Highest_bid'] : $row['Asking_price'];

                    // Close the statement
                    $stmt->close();

                    $imageRow = mysqli_fetch_assoc($queryImage);

                    // Check if the image exists and is a valid path
                    if ($imageRow && !empty($imageRow['filepath'])) {
                        $localImagePath = '../Orderlist/' . $imageRow['filepath']; // Construct the local path
                        if (file_exists($localImagePath)) {
                            $imageSrc = $localImagePath; // Use the local image path if it exists
                        }
                    }

                ?>
                    <div class="col mb-5">
                        <a onclick="openOrder('<?php echo addslashes($row['Item_number']); ?>', '<?php echo addslashes($row['Client_id']); ?>')" style="cursor: pointer; text-decoration: none;">
                            <div class="card h-100 hover-card" style="border-radius: 8px; overflow: hidden; transition: transform 0.2s;">
                                <img class="card-img-top" src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Item Picture" style="object-fit: cover; height: 200px; width: 100%;" />
                                <div class="card-body p-3">
                                    <div class="text-center">
                                        <h5 class="fw-bolder" style="font-size: 1.1rem;"><?php echo htmlspecialchars($row['Item_name']); ?></h5>
                                        <p class="text-danger" style="font-size: 1.2rem;"><?php echo '₱ ' . number_format($highestBid, 2); ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>