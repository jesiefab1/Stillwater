<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection file
include('../../../db_connection.php');

// Start session
session_start();

// Debugging: Check if the database connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the Client_id from the session
if (isset($_SESSION['Client_id'])) {
    $client_id = $_SESSION['Client_id'];
} else {
    header('Location: ../../Home/Home.php');
    exit();
}

// Initialize variables for debugging
$Item_number = null;
$message = '';
$alert_type = '';

// Check if Item_number and Client_id are set in the URL
if (isset($_GET['Item_number'])) {
    $Item_number = $_GET['Item_number'];
} elseif (isset($_POST['Item_number'])) {
    $Item_number = $_POST['Item_number'];
}

// Debugging: Log the Item_number
error_log("Item_number: " . print_r($Item_number, true));

// Check if Item_number is set
if (isset($Item_number)) {
    $query = "SELECT * FROM Item WHERE Item_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $Item_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if the item was found
    if (!$row) {
        $message = 'Item not found.';
        $alert_type = 'alert-danger';
        echo "<script>alert('$message'); window.location.href='../Home/Home.php';</script>";
        exit();
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
    exit();
}

// Debugging: Output message and alert type
error_log("Message: " . print_r($message, true));
error_log("Alert Type: " . print_r($alert_type, true));

// Output the notification script
if (isset($message) && isset($alert_type)) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        var notification = document.getElementById('notification');
        notification.className += ' $alert_type';
        notification.innerHTML = '" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "';
        notification.style.display = 'block';
    });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>Update Item</title>

    <style>
        .file-upload {
            background-color: #ffffff;
            width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        /* BUTTON */
        .file-upload-btn {
            width: 100%;
            margin: 0;
            color: #fff;
            background: #1FB264;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #15824B;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .file-upload-btn:hover {
            background: #1AA059;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .file-upload-btn:active {
            border: 0;
            transition: all .2s ease;
        }

        /* PLACEHOLDER */
        .file-upload-preview {
            display: none;
            text-align: center;
        }

        .file-upload-input {
            position: absolute;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            outline: none;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-placeholder {
            margin-top: 20px;
            border: 4px dashed #1FB264;
            position: relative;
        }

        .image-dropping,
        .file-upload-placeholder:hover {
            background-color: #1FB264;
            border: 4px dashed #fff;
        }

        .drag-text {
            text-align: center;
        }

        .drag-text h3 {
            font-weight: 100;
            text-transform: uppercase;
            color: #15824B;
            padding: 60px 0;
        }

        /* PREVIEW */
        .file-upload-image {
            max-height: 200px;
            max-width: 200px;
            margin: auto;
            padding: 20px;
        }

        /* REMOVE */
        .file-upload-remove {
            padding: 0 15px 15px 15px;
            color: #222;
        }

        .remove-image {
            width: 200px;
            margin: 0;
            color: #fff;
            background: #cd4535;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #b02818;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .remove-image:hover {
            background: #c13b2a;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .remove-image:active {
            border: 0;
            transition: all .2s ease;
        }

        #notification {
            display: none;
        }
    </style>
</head>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg fixed-top container-fluid" style="background-color: #3b1704;">
    <div class="container px-4 px-lg-5">
        <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogo.png?raw=true" id="companyLogo" class="img-fluid float-left" alt="Company Logo" style="width: 13%;">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active text-white aria-current='page'" href="../../../Home/Home.php">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white-50" href="../../aboutMe.php">About</a></li>
            </ul>
            <form class="d-flex mb-0">
                <div class="container">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" style="height: 38px;">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <ul class="navbar-nav me-auto ms-lg-4">
                    <li class="nav-item px-2 rounded">
                        <a href="../../../Orderlist/sell.php" class="btn btn-secondary rounded d-flex justify-content-center align-items-center" role="button" style="height: 44px;">
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
                        <button class="btn btn-secondary rounded-circle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 44px;">
                            <i class="bi bi-person-circle fs-5"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="../Nav/profile.php">Profile</a></li>
                            <li><a class="dropdown-item" href="../storage.php">My Items</a></li>
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

<body>
    <!-- Main layout -->
    <main class="mt-5 pt-4">
        <div class="container mt-5">
            <!-- Grid row -->
            <div class="row">
                <!-- Grid column for item image -->
                <div class="col-md-6 mb-4">
                    <?php
                    // Initialize $imageSrc with a default image
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
                    ?>
                    <img src="<?php echo $imageSrc; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($row['Item_name']); ?>" />
                </div>
                <!-- Grid column for item details -->
                <div class="col-md-6 mb-4">
                    <div class="p-4">
                        <p id="notification" class="alert"></p>
                        <form id="updateItemForm" method="post">

                            <input type="hidden" name="Item_number" value="<?php echo htmlspecialchars($row['Item_number']); ?>">

                            <h4>
                                <input type="text" name="Item_name" value="<?php echo htmlspecialchars($row['Item_name']); ?>" required>
                            </h4>
                            <p>
                                Condition:
                                <input type="text" name="Condition" value="<?php echo htmlspecialchars($row['Condition']); ?>" required>
                            </p>
                            <p>Current highest bid: ₱ <?php echo number_format($highestBid, 2); ?></p>
                            <p>First Price:
                                <input type="number" name="Asking_price" value="<?php echo number_format($row['Asking_price'], 2); ?>" required>
                            </p>
                            <strong>
                                <p style="font-size: 20px;">Description</p>
                            </strong>
                            <textarea name="Description" required><?php echo htmlspecialchars($row['Item_description']); ?></textarea>
                            <br>
                            <input type="submit" class="btn btn-success mt-2" value="Update Item">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
jQuery.noConflict();
jQuery(document).ready(function($) {
    $('#updateItemForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Create a data object to hold form data
        var formData = {
            Item_number: $('input[name="Item_number"]').val(),
            Item_name: $('input[name="Item_name"]').val(),
            Condition: $('input[name="Condition"]').val(),
            Asking_price: $('input[name="Asking_price"]').val(),
            Description: $('textarea[name="Description"]').val()
        };

        $.ajax({
            type: 'POST',
            url: 'update_item_function.php',
            data: $.param(formData),
            dataType: 'json',
            success: function(response) {
                $('#notification').removeClass('alert-danger alert-success');
                $('#notification').addClass(response.alert_type);
                $('#notification').html(response.message);
                $('#notification').show();
            },
            error: function(xhr, status, error) {
                $('#notification').removeClass('alert-danger alert-success');
                $('#notification').addClass('alert-danger');
                $('#notification').html('An error occurred: ' + xhr.responseText);
                $('#notification').show();
            }
        });
    });
});
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>