<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('../db_connection.php');

$client_id = $_SESSION['Client_id'];

// Fetch client information
$stmt = $conn->prepare("SELECT First_name, Lastname, Email FROM Client WHERE Client_id = ?");
$stmt->bind_param("s", $client_id);
$stmt->execute();
$client_result = $stmt->get_result();
$client_row = $client_result->fetch_assoc();

// Initialize variables for debugging
$Item_number = null;
$message = '';
$alert_type = '';

// Handle comment submission
if (isset($_POST['commentSubmit']) && isset($_POST['comment'])) {
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $client_name = (empty($client_row['First_name']) && empty($client_row['Lastname'])) ?
        $client_row['Email'] :
        $client_row['First_name'] . ' ' . $client_row['Lastname'];
    $full_comment = $client_name . ': ' . $comment;
    $date = date('Y-m-d H:i:s');

    $query = "UPDATE Item 
                SET Comments = CONCAT(COALESCE(Comments, ''), '\n', ?), 
                    Date_of_comments = ? 
                WHERE Item_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $full_comment, $date, $Item_number);

    if ($stmt->execute()) {
        $message = 'Comment submitted successfully.';
        $alert_type = 'alert-success';
    } else {
        $message = 'Failed to submit comment.';
        $alert_type = 'alert-danger';
    }
}

// Check if Item_number and Client_id are set in the URL
if (isset($_GET['Item_number'], $_GET['Client_id'])) {
    $Item_number = $_GET['Item_number'];
    $Client_id = $_GET['Client_id'];

    // Fetch item details
    $query = "SELECT Item.*, Client.First_name, Client.Lastname 
                FROM Item 
                JOIN Client ON Item.Client_id = Client.Client_id 
                WHERE Item_number = ? AND Client.Client_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $Item_number, $Client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if the item was found
    if (!$row) {
        $message = 'Item not found.';
        $alert_type = 'alert-danger';
    }

    // Fetch the highest bid from the Bids table
    $highestBidQuery = "SELECT MAX(Bid_amount) as Highest_bid FROM Bids WHERE Item_number = ?";
    $stmt = $conn->prepare($highestBidQuery);
    $stmt->bind_param("s", $Item_number);
    $stmt->execute();
    $highestBidResult = $stmt->get_result();
    $highestBidRow = $highestBidResult->fetch_assoc();
    $highestBid = isset($highestBidRow['Highest_bid']) ? $highestBidRow['Highest_bid'] : $row['Asking_price'];
} else {
    $message = 'Invalid request.';
    $alert_type = 'alert-danger';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .visually-hidden-focusable {
            display: none;
            /* This class hides the element */
        }

        .card {
            transition: transform 0.2s;
            /* Animation */
        }

        .card:hover {
            transform: scale(1.05);
            /* Zoom effect */
        }

        .card-img-top {
            transition: transform 0.2s;
            /* Animation */
        }

        .card-img-top:hover {
            transform: scale(1.1);
            /* Zoom effect on image */
        }

        #notification {
            display: none;
        }
    </style>
    <script>
        // Set a JavaScript variable based on the PHP login status
        let isLoggedIn = <?php echo isset($_SESSION['Client_id']) ? 'true' : 'false'; ?>;

        function goBack() {
            window.history.back(); // Go back to the previous page in the browser history
        }

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
                return false;
            } else {
                return true;
            }
        }
    </script>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top container-fluid" style="background-color: #3b1704;">
        <div class="container px-4 px-lg-5">
            <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="companyLogo" class="img-fluid float-left" alt="Company Logo" style="width: 13%;">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active text-white" href="../Home/Home.php">Home</a></li>
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
                    <?php if (!isset($_SESSION['Client_id'])) { ?>
                        <ul class="navbar-nav me-auto ms-lg-4">
                            <li class="nav-item">
                                <a href="../loginSystem/log_in.php" class="nav-link active text-white">Login</a>
                            </li>
                        </ul>
                    <?php } else { ?>
                        <div class="dropdown ms-3">
                            <button class="btn btn-secondary rounded-circle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 44px;">
                                <i class="bi bi-person-circle fs-5"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="../Nav/profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="../Nav/Dropdown/storage.php">My Items</a></li>
                                <li><a class="dropdown-item" href="../loginSystem/log_out.php">Logout</a></li>
                            </ul>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    </nav>
    <!-- Navbar -->

    <!-- Main layout -->
    <main class="mt-5 pt-4">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="container mt-4">
                        <div class="row">
                            <?php

                            $imageSrc = 'https://dummyimage.com/450x300/dee2e6/6c757d.jpg';
                            $item_number = $row['Item_number'];
                            $sqlImage = "SELECT * FROM Uploads WHERE Item_number = '$item_number'";
                            $queryImage = mysqli_query($conn, $sqlImage);
                            $imageSources = [];

                            while ($imageRow = mysqli_fetch_assoc($queryImage)) {
                                if (!empty($imageRow['filepath'])) {
                                    $imageSources[] = $imageRow['filepath'];
                                }
                            }

                            if (empty($imageSources)) {
                                $imageSources[] = $imageSrc;
                            }

                            foreach ($imageSources as $src) {
                                echo "
            <div class='col-md-4 col-sm-6 mb-4'>
                <div class='card shadow-sm'>
                    <img src='$src' class='card-img-top' alt='" . htmlspecialchars($row['Item_name']) . "' style='height: 200px; object-fit: cover;'>
                </div>
            </div>
            ";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="p-4">
                        <p id="notification" class="alert"></p>
                        <h4><?php echo htmlspecialchars($row['Item_name']); ?></h4>
                        <?php
                            // Get current date and time
                            $currentDateTime = date('Y-m-d H:i:s');
                            $endTime = $row['End_time'];
                            if ($currentDateTime < $row['End_time']) {
                                echo "Auction End Time:" . $endTime;
                            } else {
                                echo "Auction is Closed";
                                $query = "UPDATE Item SET Is_sold = 1";
                                $sql = mysqli_query($conn, $query);
                            }
                        ?>
                        <p>Condition: <?php echo $row['Condition'] ?></p>
                        <p>Current highest bid: ₱ <?php echo number_format($highestBid, 2); ?></p>
                        <p>First Price: ₱ <?php echo number_format($row['Asking_price']) ?></p>
                        <div class="form-group">
                            <label for="bidAmount">Your Bid</label>
                            <input type="number" class="form-control" id="bidAmount" name="bidAmount" min="<?php echo isset($highestBid) ? $highestBid + 1 : $row['Asking_price']; ?>" required>
                        </div>
                        <button type="button" class="btn btn-primary mt-2 placeBid" name="placeBid" data-item-number='<?php echo $row['Item_number']; ?>' onclick="logInRequired()">Place Bid</button>

                        <strong>
                            <p style="font-size: 20px;">Description</p>
                        </strong>
                        <p><?php echo nl2br(htmlspecialchars($row['Item_description'])); ?></p>
                    </div>
                </div>
            </div>
            <!-- Grid row for comments -->
            <div class="row">
                <div class="col-md-12">
                    <h5>Comments</h5>
                    <form method="POST" action="" onsubmit="handleCommentSubmission(event);">
                        <div class="form-group">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Leave a comment..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary mt-2" name="commentSubmit">Submit Comment</button>
                    </form>
                    <div class="mt-3">
                        <h6>Previous Comments:</h6>
                        <pre><?php if (isset($row['Comments'])) {
                                    echo htmlspecialchars($row['Comments']);
                                } else {
                                    echo "Be the first one to comment";
                                }
                                ?></pre>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $(".placeBid").click(function() {
                var bidAmount = $(this).closest('.p-4').find('input[name="bidAmount"]').val();
                var itemNumber = $(this).data('item-number');
                var askingPrice = <?php echo $row['Asking_price'] ?>;

                $.ajax({
                    url: 'place_bid.php',
                    type: 'POST',
                    data: {
                        Bid_amount: bidAmount,
                        Item_number: itemNumber,
                        Asking_price: askingPrice
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        $('#notification').removeClass('alert-danger alert-success');
                        $('#notification').addClass(data.alert_type);
                        $('#notification').html(data.message);
                        $('#notification').show();
                    },
                    error: function(xhr) {
                        $('#notification').removeClass('alert-danger alert-success');
                        $('#notification').addClass('alert-danger');
                        $('#notification').html('An error occurred: ' + xhr.responseText);
                        $('#notification').show();
                    }
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>