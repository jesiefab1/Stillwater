<?php
    // Include database connection file
    include 'db_connection.php';

    // Check if the user_admin is logged in
    if (!isset($_SESSION['user_admin'])) {
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        echo "<script>window.location.href = 'admin_login.php';</script>";
        exit;
    } 

    // Check if Client_id is set in the URL
    if (isset($_GET['Client_id'])) {
        $Client_id = $_GET['Client_id'];

        // Fetch the client data from the database
        $stmt = $conn->prepare("SELECT * FROM Client WHERE Client_id = ?");
        $stmt->bind_param("i", $Client_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Retrieve form data
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $phone_number = $_POST['phone_number'];
            $email = $_POST['email'];
            $address = $_POST['address'];

            // Update the client data in the database
            $stmt = $conn->prepare("UPDATE Client SET First_name = ?, Lastname = ?, Phone_number = ?, Email = ?, Address = ? WHERE Client_id = ?");
            $stmt->bind_param("sssssi", $first_name, $last_name, $phone_number, $email, $address, $Client_id);
            $stmt->execute();

            // Redirect to the client page after updating
            header("Location: client.php");
            exit();
        }
    } else {
        echo "Invalid request.";
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
        input[type="email"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Client</h1>
        <form method="POST" action="">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($client['First_name']); ?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($client['Lastname']); ?>" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($client['Phone_number']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($client['Email']); ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($client['Address']); ?>" required>

            <button type="submit">Update Client</button>
        </form>
    </div>
</body>
</html>
