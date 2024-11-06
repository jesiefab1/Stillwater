<?php
    // Start the session
    session_start();

    // Include the database connection file
    include ('db_connection.php');

    if (isset($_SESSION['user_admin'])) {
        header("Location: client.php");
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
        $query = "SELECT * FROM Admin_users WHERE Email = '$email' AND Password = '$password' AND Status = '0'";
        $result = mysqli_query($conn, $query);

        // Check if the query returned any rows
        if (mysqli_num_rows($result) > 0) {
            // Email and password are valid
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_admin'] = $email;
            echo "<script>alert('Login successful.')</script>";
            $success = true;
        } else {
            // Email or password is invalid
            $success = false;
            $failed = true;
        }
        
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Client</title>

    <script>
        // JavaScript to redirect to the client page if the insertion was successful
        <?php if ($success): ?>
        window.onload = function() {
            window.location.href = "client.php";
        };
        <?php endif; ?>
    </script>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #fff8e1;
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
            background-color: #ffb921;
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
            background-color: #ffa221;
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
            background-color: #ffb921;
            color: white;
            border-radius: 100px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .button-wrapper > button:hover {
            background-color: #ffa221;
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
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 94%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #ffb921;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #ffa221;
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
        <?php
            if ($failed) {
        ?>
                form {
                    margin-top: 15px;
                }
        <?php
            }
        ?>
    </style>

</head>
<body>

    <div class="Back">
        <button onclick="window.history.back()">Back</button>
    </div>


    <script>
        // JavaScript to add hover effects to the add button
        document.querySelector('button').addEventListener('mouseover', function() {
            this.style.backgroundColor = '#ffa221';
            this.style.transform = 'scale(1.05)';
        });
        document.querySelector('button').addEventListener('mouseout', function() {
            this.style.backgroundColor = '#ffb921';
            this.style.transform = 'scale(1)';
        });
    </script>

    <div class="container">
        <h1>Log In</h1>

        <?php 
            if ($failed) {
                echo "<span>Incorrect Email or Password</span>";
            }
        ?>

        <form action=" " method="POST">

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" name="submit" value="Enter Account">
        </form>
        <div class="button-wrapper">
            <button onclick="window.location.href='admin_createAccount.php'">
                Create Account
            </button>
        </div>
    </div>
</body>
</html>