<?php
    // Include database connection file
    include 'db_connection.php';

    // Check if form is submitted
    if(isset($_POST['submit'])) {
        $Item_name = $_POST['Item_name'];
        $Client_id = $_POST['Client_id'];
        $description = $_POST['description'];
        $asking_price = $_POST['asking_price'];
        $condition = $_POST['condition'];
    
        // Prepare and bind
        $query = "INSERT INTO Item (Client_id, Item_name, Item_description, Asking_price, `Condition`) VALUES ('$Client_id', '$Item_name', '$description', '$asking_price', '$condition')";
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
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>

    <script>

    // JavaScript to redirect to the client page if the insertion was successful
    <?php if ($success): ?>
    window.onload = function() {
        alert("Item added successfully!");
        window.location.href = "item.php";
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
            width: 400px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 95%;
            padding: 8px;
            margin-bottom: 10px;
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
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="Back">
        <button onclick="window.location.href='insert.php'">Back</button>
    </div>

    <div class="container">
        <h2>Add New Item</h2>
        <form method="POST" action="">
            <label for="item_name">Item Name:</label>
            <input type="text" name="Item_name" required>
            
            <label for="client_id">Client ID:</label>
            <select class="client_id" name="Client_id" required>
                <option value="">Select Client</option>
                <?php
                    $query = "SELECT Client_id, Lastname, First_name FROM Client";
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<option value='" . $row['Client_id'] . "'>" . $row['Lastname'] . ", " . $row['First_name'] . "</option>";
                        }
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                
                ?>
            </select>
            
            <label for="description">Description:</label>
            <input type="text" name="description" required></input>
            
            <label for="asking_price">Asking Price:</label>
            <input type="number" name="asking_price" step="0.01" required>
            
            <label for="condition">Condition:</label>
            <input type="text" name="condition" required>
            
            <input type="submit" name="submit" value="Add Item">
        </form>
    </div>

</body>
</html>