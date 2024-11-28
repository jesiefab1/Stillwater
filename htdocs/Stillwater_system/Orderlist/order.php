<?php

session_start();

include('../db_connection.php');

$client_id = $_SESSION['Client_id'];

// Fetch client information
$stmt = $conn->prepare("SELECT First_name, Lastname, Email FROM Client WHERE Client_id = ?");
$stmt->bind_param("s", $client_id);
$stmt->execute();
$client_result = $stmt->get_result();
$client_row = $client_result->fetch_assoc();

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
        echo "<script>alert('Item not found.'); window.location.href='../Home/Home.php';</script>";
        exit();
    }

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
            echo "<script>window.location.href='$_SERVER[REQUEST_URI]'</script>";
        }
    }

    // Fetch the highest bid from the Bids table
    $highestBidQuery = "SELECT MAX(Bid_amount) as Highest_bid FROM Bids WHERE Item_number = ?";
    $stmt = $conn->prepare($highestBidQuery);
    $stmt->bind_param("s", $Item_number);
    $stmt->execute();
    $highestBidResult = $stmt->get_result();
    $highestBidRow = $highestBidResult->fetch_assoc();
    $highestBid = isset($highestBidRow['Highest_bid']) ? $highestBidRow['Highest_bid'] : $row['Asking_price'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .visually-hidden-focusable {
            display: none; /* This class hides the element */
        }
        
    </style>
    <script>

        // Set a JavaScript variable based on the PHP login status
        let isLoggedIn = <?php echo isset($_SESSION['Client_id']) ? 'true' : 'false'; ?>;

        $(document).ready(function() {
            console.log("jQuery is loaded and ready to use!");
            console.log("Document is ready!");
            // Use event delegation to handle clicks on dynamically added buttons
            $(".placeBid").click(function() {
                console.log('Button clicked!'); // Debugging output
                

                // Get the bid amount from the input field
                var bidAmount = $(this).closest('.p-4').find('input[name="bidAmount"]').val(); // Use name attribute
                var itemNumber = $(this).data('item-number'); // Get the item ID
                var askingPrice = <?php echo $row['Asking_price']?>

                console.log('Bid Amount:', bidAmount); // Debugging output
                console.log('Item ID:', itemNumber); // Debugging output
                console.log('Asking Price', askingPrice);

                // Validate bid amount
                if (!bidAmount || isNaN(bidAmount) || bidAmount <= 0) {
                    var div = document.getElementById('nullInput');
                    div.style.display = 'block';
                }

                $.ajax({
                    url: 'place_bid.php', // The URL to your PHP script that handles the bid
                    type: 'POST',
                    data: {
                        Bid_amount: bidAmount,
                        Item_number: itemNumber,
                        Asking_price: askingPrice
                    },
                    success: function(response) {
                        var styledMessage = document.getElementById('successMessage');
                        styledMessage.classList.remove('visually-hidden-focusable'); // Remove the class to show the message
                        $('#successMessage').html("You have successfully bidded"); // Change the inner HTML using jQuery
                        console.log('Response:', response); // Log the response for debugging
                        var jsonResponse = JSON.parse(response); // Parse the JSON response
                        if (jsonResponse.success) {
                            alert(jsonResponse.message); // Show success message
                        } else {
                            alert(jsonResponse.message); // Show error message
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                        alert('Error placing bid: ' + xhr.responseText);
                    }
                });
            });
        });
    
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-link text-white-50" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Categories</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">All Products</a></li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                            <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                            <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex mb-0">
                    <div class="container">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
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
            <!-- Grid row -->
            <div class="row">
                <!-- Grid column for item image -->
                <div class="col-md-6 mb-4">
                    <img src="<?php echo $row['Image_url']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($row['Item_name']); ?>" />
                </div>
                <!-- Grid column for item details -->
                <div class="col-md-6 mb-4">
                    <div class="p-4">
                        <h4><?php echo htmlspecialchars($row['Item_name']); ?></h4>
                        <p>Condition: <?php echo $row['Condition']?></p>
                        <p>Current highest bid: ₱ <?php echo number_format($highestBid, 2); ?></p>
                        <p>First Price: ₱ <?php echo number_format($row['Asking_price'])?></p>
                        <!--
                        <div class="bg-success p-1 mb-2 text-center fw-bold border rounded text-white background-color-danger" style="display: none;">
                            <p class="mb-1 mt-1" id="successMessage">You have successfully bidded</p>
                        </div>
                        <div class="bg-danger p-1 mb-2 text-center fw-bold border rounded text-white background-color-danger" style="display: none;">
                            <p class="mb-1 mt-1" id="nullInput">Pls input a number first</p>
                        </div>
                        <div class="bg-danger p-1 mb-2 text-center fw-bold border rounded text-white background-color-danger" style="display: none;">
                            <p class="mb-1 mt-1" id="mustBidHigh">You must bid higher than the current amount</p>
                        </div>
                        <div class="bg-danger p-1 mb-2 text-center fw-bold border rounded text-white background-color-danger" style="display: none;">
                            <p class="mb-1 mt-1" id="missingLogin">You must login or register before buying an item</p>
                        </div>
                        -->
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

</body>

</html>