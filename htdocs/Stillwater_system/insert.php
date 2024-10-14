<?php
    include 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        .Back {
            text-align: right;
        }
        .Back > button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100px;
            border-radius: 5px;
        }
        .insertion {
            text-align: center;
            margin-top: 15%;
            border-radius: 5px;
        }
        .insertion > button {
            background-color: white;
            border-color: #4CAF50;
            color: #4CAF50;
            padding: 14px 20px;
            margin: 8px 0;
            cursor: pointer;
            width: 200px;
            border-radius: 5px;
            transition: ease-in .15s;
        }

        .insertion > button:hover {
            background-color: #4CAF50;
            color: white;
        }

    </style>
</head>
<body>

    <div class="Back">
        <button onclick="window.location.href='client.php'">Back</button>
    </div>

    <div class="insertion">
        <button onclick="window.location.href='add_client.php'">
            Add Client
        </button>
        <button onclick="window.location.href='add_item.php'">
            Add Item
        </button>    
    </div>
    
</body>
</html>