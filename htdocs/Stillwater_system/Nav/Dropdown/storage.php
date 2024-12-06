<?php
// Start the session
session_start();

include('../../db_connection.php');

// Get the Client_id from the session
if (isset($_SESSION['Client_id'])) {
    $client_id = $_SESSION['Client_id'];
} else {
    header('Location: ../../Home/Home.php');
    exit;
}

// Prepare the SQL query to fetch data for the specific client_id
if (isset($_SESSION['Client_id'])) {
    $query = "SELECT * FROM Item WHERE Client_id = '$client_id'";
    $result = mysqli_query($conn, $query);
}

function updateButton($Item_number)
{
    echo '<button onclick="window.location.href=\'client_update_item.php?Item_number=' . $Item_number . '\'" class="updateButton">
        Update
        </button>';
}

function deleteButton($Item_number)
{
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <script>
        function openOrder1(Item_number) {
            // Construct the URL with query parameters
            const url = `clientsItem/client_update_item.php?Item_number=${Item_number}`;
            // Redirect to the order page
            window.location.href = url;
        }
    </script>
    <style>
    .hover-card {
        transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for effects */
    }

    .hover-card:hover {
        transform: scale(1.05); /* Slightly enlarge the card */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Add shadow effect */
        border: 2px solid #007bff; /* Optional: add a border on hover */
    }
    </style>

    <title>Navigation Menu</title>

</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg fixed-top container-fluid" style="background-color: #3b1704;">
        <div class="container px-4 px-lg-5">
        <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogoNew1.png?raw=true" id="companyLogo" class="img-fluid float-left" alt="Company Logo" style="width: 13%;">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active text-white aria-current='page'" href="../../Home/Home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="../../Nav/aboutMe.php">About</a></li>
                </ul>
                <form class="d-flex">
                    <ul class="navbar-nav me-auto ms-lg-4">
                        <li class="nav-item px-2 rounded">
                            <a href="../../Orderlist/sell.php" class="btn btn-secondary rounded" role="button">
                                <i class="bi bi-cart4 fs-5"></i>
                            </a>
                        </li>
                    </ul>

                    <?php
                    if (!isset($_SESSION['Client_id'])) {
                    ?>
                        <ul class="navbar-nav me-auto ms-lg-4" id="Shop">
                            <li class="nav-item">
                                <a href="../../loginSystem/log_in.php" class="nav-link active text-white">Login</a>
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
                                <li><a class="dropdown-item" href="storage.php">My Items</a></li>
                                <li><a class="dropdown-item" href="../../loginSystem/log_out.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php
                    }
                    ?>

                </form>
            </div>
        </div>
    </nav>
    <section class="py-5" background-color: #d4d6d9;>
        <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-lg-5 justify-content-center">
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

                            $imageSrc = 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg'; // Default image

                            // Fetch the image for the current item
                            $item_number = $row['Item_number'];
                            $sqlImage = "SELECT * FROM Uploads WHERE Item_number = '$item_number'"; // Use quotes around $item_number
                            $queryImage = mysqli_query($conn, $sqlImage);

                            $imageRow = mysqli_fetch_assoc($queryImage);


                            // Check if the image exists and is a valid path
                            if ($imageRow && !empty($imageRow['filepath'])) {
                                $localImagePath = '../../Orderlist/' . $imageRow['filepath']; // Construct the local path
                                if (file_exists($localImagePath)) {
                                    $imageSrc = $localImagePath; // Use the local image path if it exists
                                }
                            }

                            if (!$result) {
                                die("Query failed: " . mysqli_error($conn));
                            }
                ?>
                            <div class="col mb-5">
                                <a onclick="openOrder1(<?php echo $row['Item_number']; ?>); return false;" style="cursor: pointer; text-decoration: none;">
                                    <div class="card h-100 hover-card" style="border-radius: 8px; overflow: hidden; transition: transform 0.2s;">
                                        <img class="card-img-top" src="<?php echo htmlspecialchars($imageSrc); ?>" alt="Item Picture" style="object-fit: cover; height: 200px; width: 100%;" />
                                        <div class="card-body p-3">
                                            <div class="text-center">
                                                <h5 class="fw-bolder" style="font-size: 1.1rem;"><?php echo htmlspecialchars($row['Item_name']); ?></h5>
                                                <p class="text-danger" style="font-size: 1.2rem;"><?php echo '₱ ' . number_format($row['Asking_price'], 2); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>