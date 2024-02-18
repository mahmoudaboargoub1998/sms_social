<?php


// Accessing the data
$firstName = "";
$lastName = "";
$dob = "";
$email = "";

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>After SignUp</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .main-div {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        p {
            margin: 8px 0;
        }
        .redirect-button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="main-div">
        <h2>User Information</h2>
        <p><strong>First Name:</strong> <?=$firstName?></p>
        <p><strong>Last Name:</strong> <?=$lastName?></p>
        <p><strong>Date of Birth:</strong> <?=$dob?></p>
        <p><strong>Email:</strong> <?=$email?></p>
        <button class="redirect-button" ><a href="./home.php">Go to Home</a></button>
    </div>
</body>
</html>

