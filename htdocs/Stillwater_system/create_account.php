<?php
    session_start();

    // Include the database connection file
    include ('db_connection.php');

    // Check if the form is submitted
    if(isset($_POST['submit'])) {

        // Get form data
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // Prepare the SQL query
        $query = "INSERT INTO Client (First_name, Lastname, Email, Password, Phone_number, Address) VALUES ('$first_name', '$last_name', '$email', '$password', '$phone', '$address')";
        
        // Execute the SQL query
        if ($result = mysqli_query($conn, $query)) {
            $query1 = "SELECT * FROM Client WHERE Email = '$email' AND Password = '$password'";
            $result1 = mysqli_query($conn, $query1);

            if (mysqli_num_rows($result1) > 0) {
                // Email and password are valid
                $row = mysqli_fetch_assoc($result1);
                $_SESSION['Client_id'] = $row['Client_id'];
                $_SESSION['First_name'] = $row['First_name'];
                $_SESSION['Lastname'] = $row['Lastname']; // Store Client_id in session
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
            window.location.href = "buy.php";
        };
        <?php endif; ?>
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
            transition: background-color 0.3s, transform 0.3s;
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
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="Back">
        <button onclick="window.location.href='client.php'">Back</button>
    </div>

    <div class="container">
        <h1>Create New Account</h1>
        <form action=" " method="POST">
            <label for="name">First Name:</label>
            <input type="text" name="first_name" required>

            <label for="name">Last Name:</label>
            <input type="text" name="last_name" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>
            
            <label for="phone">Phone:</label>
            <input type="text" name="phone" required>
            
            <label for="address">Address:</label>
            <input type="text" name="address" required>
            
            <input type="submit" name="submit" value="Add Client">
        </form>
        <div class="button-wrapper">
            <button onclick="window.location.href='log_in.php'">
                Log In
            </button>
        </div>
    </div>
</body>
</html>
