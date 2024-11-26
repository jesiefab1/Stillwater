<?php
// Start the session
session_start();

include('../db_connection.php');

// Get the Client_id from the session
if (isset($_SESSION['Client_id'])) {
    $client_id = $_SESSION['Client_id'];
} else {
    header('Location: ../Home/Home.php');
    exit;
}
$noLogin = !isset($_SESSION['Client_id']); // Check if the user is not logged in

// Check if form is submitted
if (isset($_POST['submit'])) {
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
        $item_number = mysqli_insert_id($conn);
        echo "<script>alert('Item added successfully!');</script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}

// Handle file upload
if (isset($_FILES['itemImage']) && $_FILES['itemImage']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['itemImage']['tmp_name'];
    $fileName = $_FILES['itemImage']['name'];
    $fileSize = $_FILES['itemImage']['size'];
    $fileType = $_FILES['itemImage']['type'];

    // File validation and processing
    $allowedFilesTypes = ['image/jpeg', 'image/jpg', 'image/png']; // jpeg, jpg, png
    $maxFilesSizeLimit = 2 * 1024 * 1024; // 2mb max

    if (!in_array($fileType, $allowedFilesTypes)) {
        echo "<script> alert('Uploaded file not in allowed list')</script>;";
    }

    if ($fileSize > $maxFilesSizeLimit) {
        echo "<script> alert('Uploaded file has exceeded the allowed limit')</script>;";
    }

    // Specify the directory where the file will be saved
    $uploadFileDir = 'uploads/' . basename($fileName);

    // Move the file to the specified directory
    if(move_uploaded_file($fileTmpPath, $uploadFileDir)) {
        // Prepare and bind
        $query = "INSERT INTO Uploads (Item_number, filepath) VALUES ('$item_number', '$uploadFileDir')";
        $result = mysqli_query($conn, $query);

        // Check if the query is executed
        if ($result) {
            echo "<script>alert('Item added successfully!');</script>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error moving the uploaded file.";
    }
} else {
    echo "No file uploaded or there was an upload error.";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <title>Navigation Menu</title>

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
    </style>

    <script>
        // Set a JavaScript variable based on the PHP login status
        var isLoggedIn = <?php echo json_encode(!$noLogin); ?>; // true if logged in, false otherwise

        function logInRequired() {
            if (!isLoggedIn) {
                alert("You must be logged in to sell an Item"); // Alert the user
                window.location.href = 'log_in.php'; // Redirect to the login page
            }
        }

        function readURL(input) {
            if (input.files && input.files[0]) { // if input is file, files has content
                var inputFileData = input.files[0]; // shortcut
                var reader = new FileReader(); // FileReader() : init
                reader.onload = function(e) {
                    /* FileReader : set up ************** */
                    console.log('e', e)
                    $('.file-upload-placeholder').hide(); // call for action element : hide
                    $('.file-upload-image').attr('src', e.target.result); // image element : set src data.
                    $('.file-upload-preview').show(); // image element's container : show
                    $('.image-title').html(inputFileData.name); // set image's title
                    document.getElementById('addImageBtn').style.display = 'none';
                    document.getElementById('removeImageBtn').style.display = 'inline-block';
                };
                console.log('input.files[0]', input.files[0])
                reader.readAsDataURL(inputFileData); // reads target inputFileData, launch `.onload` actions
            } else {
                removeUpload();
            }
        }

        function removeUpload() {
            var $clone = $('.file-upload-input').val('').clone(true); // create empty clone
            $('.file-upload-input').replaceWith($clone); // reset input: replaced by empty clone
            $('.file-upload-placeholder').show(); // show placeholder
            $('.file-upload-preview').hide(); // hide preview

            // Show the Add Image button and hide the Remove Image button
            document.getElementById('addImageBtn').style.display = 'inline-block';
            document.getElementById('removeImageBtn').style.display = 'none';
        }

        // Style when drag-over
        $('.file-upload-placeholder').bind('dragover', function() {
            $('.file-upload-placeholder').addClass('image-dropping');
        });
        $('.file-upload-placeholder').bind('dragleave', function() {
            $('.file-upload-placeholder').removeClass('image-dropping');
        });
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
                    <li class="nav-item"><a class="nav-link active text-white aria-current='page'" href="../Home/Home.php">Home</a></li>
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
                                <button class="btn btn-primary" type="button" style="height: 38px;">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <ul class="navbar-nav me-auto ms-lg-4">
                        <li class="nav-item px-2 rounded">
                            <a href="../Orderlist/sell.php" class="btn btn-secondary rounded d-flex justify-content-center align-items-center" role="button" style="height: 44px;">
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
    <!-- Section-->
    <section class="py-5" style="background-color: #d4d6d9;">
        <div class="container mt-5">
            <h2>Sell Your Item</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="file-upload">

                <button class="file-upload-btn" type="button" id="addImageBtn" onclick="$('.file-upload-input').trigger( 'click' )">Add Image</button>

                    <div class="file-upload-placeholder">
                        <input class="file-upload-input" type='file' name="itemImage" onchange="readURL(this);" accept="image/*">
                        <div class="drag-text">
                            <h3>Drag and drop a file or select add Image</h3>
                        </div>
                    </div>

                    <div class="file-upload-preview">
                        <img class="file-upload-image" src="#" alt="your image" />
                        <div class="file-upload-remove">
                            <button type="button" onclick="removeUpload()" class="remove-image" id="removeImageBtn">Remove Uploaded Image</button>
                        </div>
                    </div>

                </div>
                <div class="mb-3">
                    <label for="Item_name" class="form-label">Item Name</label>
                    <input type="text" class="form-control" id="Item_name" name="Item_name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="asking_price" class="form-label">Asking Price</label>
                    <input type="number" class="form-control" id="asking_price" name="asking_price" required>
                </div>
                <div class="mb-3">
                    <label for="condition" class="form-label">Condition</label>
                    <select class="form-select" id="condition" name="condition" required>
                        <option value="" disabled selected>Select condition</option>
                        <option value="Very Good">Very Good</option>
                        <option value="Good">Good</option>
                        <option value="Bad">Bad</option>
                        <option value="Very Bad">Very Bad</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </section>
    <?php
        if (isset($_POST['submit'])) {
            echo "<pre>";
                print_r($_FILES['itemImage']);
            echo "<pre>";
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
</body>

</html>