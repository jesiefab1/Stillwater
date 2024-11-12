<?php
    // Start the session
    session_start();

    // Include the database connection file
    include ('../db_connection.php');

    require 'libs/google-api-php-client/vendor/autoload.php';


    if (isset($_SESSION['Client_id'])) {
        header("Location: Home.php");
        exit;
    }

    $failed = false;

    // Store the previous page URL in the session
    if (!isset($_SESSION['previous_page'])) {
        // Use HTTP_REFERER if available, otherwise fallback to 'Home.php'
        $_SESSION['previous_page'] = $_SERVER['HTTP_REFERER'] ?? 'Home.php';
    }

    $previous_page = $_SESSION['previous_page'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare the SQL query to validate email and password
        $query = "SELECT * FROM Client WHERE Email = '$email' AND Password = '$password' AND Status = '0'";
        $result = mysqli_query($conn, $query);

        // Check if the query returned any rows
        if (mysqli_num_rows($result) > 0) {
            header('Location: ' . $_SESSION['previous_page']);
            // Email and password are valid
            $row = mysqli_fetch_assoc($result);
            $_SESSION['Client_id'] = $row['Client_id'];
            $_SESSION['First_name'] = $row['First_name'];
            $_SESSION['Lastname'] = $row['Lastname'];// Store Client_id in session
            

        } else {
            $failed = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Add Client</title> 

    <script>
        function goBack() {
            window.history.back();
        }
        
        function handleCredentialResponse(response) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'callback.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Handle successful login
                    console.log('Login successful:', xhr.responseText);
                    window.location.reload(); // Reload to show user info
                } else {
                    console.error('Login failed:', xhr.responseText);
                }
            };
            xhr.send('id_token=' + response.credential);
        }

        window.onload = function () {
            google.accounts.id.initialize({
                client_id: '175487461829-um8ubpj71oi097ug21komlb88f52qa5p.apps.googleusercontent.com',
                callback: handleCredentialResponse
            });
            google.accounts.id.renderButton(
                document.getElementById("g_id_signin"),
                { theme: "outline", size: "large" }
            );
        };
    </script>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .Back {
            text-align: right;
        }
        .Back > button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 16px 8px;
            border: none;
            cursor: pointer;
            width: 100px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .Back > button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .button-wrapper {
            margin-top: 10px;
            width: 100%;
            display: flex;
            justify-content: center;
        }
        .button-wrapper > button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border-radius: 100px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .button-wrapper > button:hover {
            background-color: #45a049;
            transform: scale(1.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label, small {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="email"],
        input[type="password"] {
            width: 94%;
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
            background-color: #218838;
        }
        span {
            color: #fa3c5c;
            background-color: #ffc0cb;
            padding-right: 16.5%;
            padding-left: 16.5%;
            padding-top: 10px;
            padding-bottom: 10px;
            border-radius: 5px;
        }
        .btn btn-primary btn-floating mx-1 rounded-circle {
            width: 40px;
        }
        .divider:after,
        .divider:before {
        content: "";
        flex: 1;
        height: 1px;
        background: #eee;
        }
        .h-custom {
        height: calc(100% - 73px);
        }
        @media (max-width: 450px) {
        .h-custom {
        height: 100%;
        }
        }
    </style>

</head>
<body>

    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <section class="vh-100">
    <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
            class="img-fluid" alt="Sample image">
        </div>
        <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <form>
            <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                <p class="lead fw-normal mb-0 me-3">Sign in with</p>
                <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1 rounded-circle">
                <i class="fab fa-facebook-f"></i>
                </button>

                <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1 rounded-circle" style="width: 40px;">
                <i class="fab fa-twitter"></i>
                </button>

                <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1 rounded-circle">
                <i class='fab fa-google'></i>
                </button>
            </div>

            <div class="divider d-flex align-items-center my-4">
                <p class="text-center fw-bold mx-3 mb-0">Or</p>
            </div>

            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" id="form3Example3" class="form-control form-control-lg"
                placeholder="Enter a valid email address" />
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-3">
                <input type="password" id="form3Example4" class="form-control form-control-lg"
                placeholder="Enter password" />
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <!-- Checkbox -->
                <div class="form-check mb-0">
                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                <label class="form-check-label" for="form2Example3">
                    Remember me
                </label>
                </div>
                <a href="#!" class="text-body">Forgot password?</a>
            </div>

            <div class="text-center text-lg-start mt-4 pt-2">
                <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="#!"
                    class="link-danger">Register</a></p>
            </div>

            </form>
        </div>
        </div>
    </div>
    </section>
</body>
</html>