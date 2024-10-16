<?php

session_start();

$client_id = $_SESSION['Client_id'];

// Include database connection file
include 'db_connection.php';

// Check if Client_id is set in the URL
if (isset($_GET['Item_number'])) {
    $Item_number = $_GET['Item_number'];

    // Fetch the client data from the database
    $stmt = $conn->prepare("SELECT * FROM Item WHERE Item_number = ?");
    $stmt->bind_param("i", $Item_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if (!$item) {
        echo "Item not found.";
        exit();
    }

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve form data
        $Item_name = $_POST['Item_name'];
        $Item_description = $_POST['Item_description'];
        $Asking_price = $_POST['Asking_price'];
        $Condition = $_POST['Condition'];

        $query = "UPDATE Item SET Item_name = '$Item_name', Item_description = '$Item_description', Asking_price = '$Asking_price', `Condition` = '$Condition' WHERE Item_number = '$Item_number' AND Client_id = '$client_id'";
        $result1 = mysqli_query($conn, $query);
        if ($result1) {
            $success = true;
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }

    }
else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>

    <script>

    // JavaScript to redirect to the client page if the insertion was successful
    <?php if ($success): ?>
    window.onload = function() {
        alert("Item updated successfully!");
        window.location.href = "storage.php";
    };
    <?php endif; ?>

    </script>

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
        input[type="number"],
        select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .client_id {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
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
        <h1>Update Item</h1>
        <form method="POST" action="">

            <label for="Item_name">Item Name:</label>
            <input type="text" id="Item_name" name="Item_name" value="<?php echo htmlspecialchars($item['Item_name']); ?>" required>

            <label for="Item_description">Item Description:</label>
            <input type="text" id="Item_description" name="Item_description" value="<?php echo htmlspecialchars($item['Item_description']); ?>" required>

            <label for="Asking_price">Asking Price:</label>
            <input type="number" id="Asking_price" name="Asking_price" value="<?php echo htmlspecialchars($item['Asking_price']); ?>" required>

            <label for="Condition">Condition:</label>
            <input type="text" id="Condition" name="Condition" value="<?php echo htmlspecialchars($item['Condition']); ?>" required>

            <button type="submit">Update Item</button>
        </form>
    </div>
</body>
</html>
