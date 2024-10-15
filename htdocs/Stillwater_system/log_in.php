<?php
    // Include the database connection file
    include ('db_connection.php');

    // Check if the form is submitted
    if(isset($_POST['submit'])) {

        // Get form data
        $first_name = $_POST['first_name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        // Prepare the SQL query
        $query = "INSERT INTO Client (First_name, Lastname, Email, Password, Phone_number, Address) VALUES ('$first_name', '$lastname', '$email', '$password', '$phone', '$address')";
        
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
            margin-top: auto; /* Pushes the button to the bottom */
        }
        .button-wrapper > button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border-radius: 100px;
            border: none;
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

    <script>
        // JavaScript to add hover effects to the add button
        document.querySelector('button').addEventListener('mouseover', function() {
            this.style.backgroundColor = '#45a049';
            this.style.transform = 'scale(1.05)';
        });
        document.querySelector('button').addEventListener('mouseout', function() {
            this.style.backgroundColor = '#4CAF50';
            this.style.transform = 'scale(1)';
        });
    </script>

    <div class="container">
        <h1>Log In</h1>
        <form action="buy.php" method="POST">

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" name="submit" value="Enter Account">
        </form>
        <div class="button-wrapper">
            <button onclick="window.location.href='create_account.php'">
                Create Account
            </button>
        </div>
    </div>
</body>
</html>