<?php
    // Include database connection file
    include('../db_connection.php');

    session_start();

    $_SESSION['UserType'] = "Admin";
    // Check if the user_admin is logged in
    if (!isset($_SESSION['UserType'])) {
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
            echo "<script>window.close();</script>";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Update Client</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title text-center">Update Client</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($client['First_name']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($client['Lastname']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($client['Phone_number']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($client['Email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($client['Address']); ?>">
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Update Client</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
