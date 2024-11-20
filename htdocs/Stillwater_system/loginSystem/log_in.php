<?php
    // Start the session
    session_start();

    // Include the database connection file
    include ('../db_connection.php');

    require 'libs/google-api-php-client/vendor/autoload.php';

    if (isset($_SESSION['Client_id'])) {
        header("Location: ../Home/Home.php");
        exit();
    }

    $failed = false;

    // Store the previous page URL in the session
    if (!isset($_SESSION['previous_page'])) {
        // Use HTTP_REFERER if available, otherwise fallback to 'Home.php'
        $_SESSION['previous_page'] = $_SERVER['HTTP_REFERER'] ?? '../Home/Home.php';
    }

    $previous_page = $_SESSION['previous_page'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data
        $email1 = $_POST['text'];
        $password1 = $_POST['password'];

        $query2 = "SELECT * FROM Admin_users WHERE Username= '$email1' AND Password = '$password1'";
        $Query2 = $conn -> query($query2);
        $Query2_Result = $Query2 -> fetch_assoc();

        if (isset($Query2_Result)){
            $_SESSION['Client_id'] = $Query2_Result['Admin_id'];
            $_SESSION['UserType'] = "Admin";
            header("Location: ../dashmin-1.0.0/tableClient.php");
            exit();
        }

        // Prepare the SQL query to validate email and password
        $query = "SELECT * FROM Client WHERE Email = '$email1' AND Password = '$password1' AND Status = '0'";
        $result = mysqli_query($conn, $query);

        // Check if the query returned any rows
        if (mysqli_num_rows($result) > 0) {
            header('Location: ' . $previous_page);
            // Email and password are valid
            $row = mysqli_fetch_assoc($result);
            $_SESSION['Client_id'] = $row['Client_id'];
            exit();

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Add Client</title> 

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
            <form method="POST" action="">
            <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">
                <p class="lead fw-normal mb-0 me-3">Sign in with</p>
                <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1 rounded-circle">
                <i class="fab fa-facebook-f"></i>
                </button>

                <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1 rounded-circle" style="width: 40px;">
                <i class="fab fa-twitter"></i>
                </button>

                <button  type="button" id="google-signin-btn" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-floating mx-1 rounded-circle">
                <i class='fab fa-google'></i>
                </button>
            </div>

            <div class="divider d-flex align-items-center my-4">
                <p class="text-center fw-bold mx-3 mb-0">Or</p>
            </div>

            <?php
            if ($failed) {
            ?>
                <div class="bg-danger p-1 mb-2 text-center fw-bold border border-danger rounded">
                    <p class="mb-1 mt-1">Invalid Email or Password</p>
                </div>
            <?php
            }
            ?>

            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" id="form3Example3" class="form-control form-control-lg"
                placeholder="Enter a valid email address" name="text" required/>
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-3">
                <input type="password" id="form3Example4" class="form-control form-control-lg"
                placeholder="Enter password" name="password" required/>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <!-- Checkbox -->
                <div class="form-check mb-0">
                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                <label class="form-check-label" for="form2Example3">
                    Remember me
                </label>
                </div>
                <a href="#!" class="text-body text-decoration-none fw-bold">Forgot password?</a>
            </div>

            <div class="text-center text-lg-start mt-4 pt-2">
                <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? 
                <a href="create_account.php"class="link-danger text-decoration-none">Register</a></p>
            </div>

            </form>
        </div>
        </div>
    </div>
    </section>

    <script>
        function goBack() {
            window.history.back();
        }

        const hash = window.location.hash;
            if (hash) {
                const params = new URLSearchParams(hash.substring(1)); //Removes the '#' and pars params
                const idToken = params.get('id_token')

                if (idToken) {
                    console.log("Sending ID token:", idToken); // Log the ID token
                    $.ajax({
                        url: 'login_function.php', //URL here
                        type: 'POST',
                        data: { id_token: idToken },
                        success: function(response) {
                            window.location.href = "../Home/Home.php";
                            console.log('Response from server', response);
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error)
                        }
                    });
                } else {
                    console.log("No ID token found in the URL")
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('google-signin-btn').onclick = function() {
                    function generateNonce() {
                        return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                    }

                    const nonce = generateNonce();
                    const clientId = '175487461829-um8ubpj71oi097ug21komlb88f52qa5p.apps.googleusercontent.com'; // Client ID Here
                    // Change the redirectUri Based on your {LoginPage.php} Web Link (Get the link when you open the {LoginPage.php} Page and replace here)
                    const redirectUri = 'https://effective-capybara-6944vgj6jjq72rxvw-8000.app.github.dev/htdocs/Stillwater_system/loginSystem/log_in.php'; // Changed from redirectUrl to redirectUri
                    const scope = 'openid email profile';
                    const responseType = 'id_token';
                    const prompt = 'select_account';
                    const authUrl = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${clientId}&scope=${scope}&response_type=${responseType}&redirect_uri=${encodeURIComponent(redirectUri)}&prompt=${prompt}&nonce=${nonce}`;
                    window.location.href = authUrl;
                };
            });
    </script>
</body>
</html>