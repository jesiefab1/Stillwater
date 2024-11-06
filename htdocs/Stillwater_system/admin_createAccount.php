<?php
    session_start();

    // Include the database connection file
    include ('db_connection.php');

    if (isset($_SESSION['user_admin'])) {
        header("Location: client.php");
        exit;
    }

    // Check if the form is submitted
    if(isset($_POST['submit'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $status = 0;

        // Prepare the SQL query
        $query = "INSERT INTO Admin_users (Email, Password, Status) VALUES ('$email', '$password', '$status')";
        
        // Execute the SQL query
        if ($result = mysqli_query($conn, $query)) {
            $query1 = "SELECT * FROM Admin_users WHERE Email = '$email' AND Password = '$password'";
            $result1 = mysqli_query($conn, $query1);

            if (mysqli_num_rows($result1) > 0) {
                // Email and password are valid
                $row = mysqli_fetch_assoc($result1);
                $_SESSION['user_admin'] = $email;
                echo "<script>alert('Account created successfully.')</script>";
                $success = true;
            }
        } else {
            echo "<script>alert('Error creating account.')</script>";
            $success = false;
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
            transition: background-color 0.3s, transform 0.3s;
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
            width: 95%;
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
    </style>
</head>
<body>

    <div class="container">
        <h1>Signup</h1>
        <form action=" " method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" name="submit" value="Create Account">
        </form>
        <div class="button-wrapper">
            <button onclick="window.location.href='admin_login.php'">
                Log In
            </button>
        </div>
    </div>
</body>
</html>
