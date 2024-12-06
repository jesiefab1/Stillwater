<?php
// Start the session
session_start();

include '../db_connection.php';

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
    // Convert end time from 'YYYY-MM-DDTHH:MM' to 'YYYY-MM-DD HH:MM:SS'
    $end_time = str_replace('T', ' ', $_POST['end_time']);
    $is_sold = 0;

    // Prepare and bind
    $query = "INSERT INTO Item (Client_id, Item_name, Item_description, Asking_price, `Condition`, End_time, Is_sold) VALUES ('$client_id', '$Item_name', '$description', '$asking_price', '$condition', '$end_time', '$is_sold')";
    $result = mysqli_query($conn, $query);

    // Check if the query is executed
    if ($result) {
        $item_number = mysqli_insert_id($conn);
        echo "<script>alert('Item added successfully!');</script>";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    if ($result) {
        $item_number = mysqli_insert_id($conn);

        // Handle multiple file uploads
        if (isset($_FILES['itemImages']) && count($_FILES['itemImages']['name']) > 0) {
            $allowedFilesTypes = ['image/JPG', 'image/jpeg', 'image/jpg', 'image/png']; // Allowed file types
            $maxFileSizeLimit = 2 * 1024 * 1024; // 2 MB max size

            foreach ($_FILES['itemImages']['name'] as $index => $name) {
                $fileTmpPath = $_FILES['itemImages']['tmp_name'][$index];
                $fileType = $_FILES['itemImages']['type'][$index];
                $fileSize = $_FILES['itemImages']['size'][$index];

                if (!in_array($fileType, $allowedFilesTypes)) {
                    echo "<script>alert('File $name is not a valid image type.')</script>";
                    continue;
                }

                if ($fileSize > $maxFileSizeLimit) {
                    echo "<script>alert('File $name exceeds the maximum size limit.')</script>";
                    continue;
                }

                $newFileName = uniqid() . '_' . $name;
                $uploadFileDir = 'uploads/' . $newFileName;

                if (move_uploaded_file($fileTmpPath, $uploadFileDir)) {
                    $query = "INSERT INTO Uploads (Item_number, filepath, Status) VALUES ('$item_number', '$uploadFileDir', '0')";
                    mysqli_query($conn, $query);
                } else {
                    echo "<script>alert('Failed to upload $name.')</script>";
                }
            }
        }
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

        .preview-container img {
            max-height: 150px;
            max-width: 150px;
            margin: 10px;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .preview-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 5px;
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
        <img src="https://github.com/jesiefab1/Stillwater/blob/main/htdocs/Images/companyLogoNew1.png?raw=true" id="companyLogo" class="img-fluid float-left" alt="Company Logo" style="width: 13%;">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active text-white aria-current='page'" href="../Home/Home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white-50" href="../Nav/aboutMe.php">About</a></li>
                </ul>
                <form class="d-flex mb-0">
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
    <section class="py-5">
        <div class="container" style="margin-top: 40px;">
            <h2>Sell Your Item</h2>
            <form method="POST" action="" enctype="multipart/form-data">
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
                <div class="mb-3">
                    <label for="end_time" class="form-label">End Time:</label>
                    <input type="datetime-local" class="form-control" name="end_time" required>
                </div>
                <div class="mb-3">
                    <label for="itemImages" class="form-label">Upload Images</label>
                    <input type="file" class="form-control" id="itemImages" name="itemImages[]" multiple accept="image/*" onchange="previewImages()">
                    <div class="preview-container" id="imagePreviewContainer"></div>
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
    <script>
        function previewImages() {
            const files = document.getElementById('itemImages').files;
            const container = document.getElementById('imagePreviewContainer');
            container.innerHTML = ''; // Clear existing previews

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    container.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>

</html>
</body>

</html>