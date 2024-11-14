<?php
    // Include the database connection file
    include ('db_connection.php');

    session_start();

    // Check if the user_admin is logged in
    if (!isset($_SESSION['user_admin'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'admin_login.php';</script>";
        exit;
    } 

    // Check if the form is submitted
    if(isset($_POST['submit'])) {

        // Get form data
        $first_name = $_POST['first_name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $status = 0;

        // Prepare the SQL query
        $query = "INSERT INTO Client (First_name, Lastname, Email, Phone_number, Address, Status) VALUES ('$first_name', '$lastname', '$email', '$phone', '$address', '$status')";
        
        // Execute the SQL query
        $result = mysqli_query($conn, $query);

        // Check if the query is executed
        if ($result) {
            $success = true;
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
    <title>Add Client</title>

    <script>

        // JavaScript to redirect to the client page if the insertion was successful
        <?php if ($success): ?>
        window.onload = function() {
            alert("Client added successfully!");
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
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 16px 8px;
            border: none;
            cursor: pointer;
            width: 100px;
            border-radius: 5px;
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
        <h1>Add New Client</h1>
        <form action="" method="POST">
            <label for="name">First Name:</label>
            <input type="text" name="first_name" required>

            <label for="name">Last Name:</label>
            <input type="text"  name="lastname" required>
            
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
    </div>
</body>
</html>