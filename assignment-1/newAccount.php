<?php
require_once './smsphp/user.php';
require_once './smsphp/auth.php';
require './helper_function/helper.php';
require_once './smsphp/session_handler.php';

SMSSessionHandler::start();

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && !areAllValuesEmpty($_POST)) {
     try {

     $auth = new Auth(new DatabaseConnection('localhost','root','','social_sms'));
     
     $newUser = new User();
     $newUser->updateFromPostArray($_POST);
     $result = $auth->signup($newUser);
     SMSSessionHandler::set('id',$result ? $result : null);
     SMSSessionHandler::set('fname',$newUser->getFirstName());
     SMSSessionHandler::set('lname',$newUser->getLastName());
     SMSSessionHandler::set('email',$newUser->getEmail());
     SMSSessionHandler::set('dob',$newUser->getDOB());
     header("Location: index.php");
     exit();
     } catch (\Throwable $th) {
     $message = $th->getMessage();
     echo $message;
     }
     }else{
        $message = "no input data";
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="./newAccount.css">
</head>
<body>
    <div class="Sing_Up">
        <h1>Sign Up </h1>
        <h4>its free and only takes a minute</h4> 
        <form action="" method="POST">
            <label>First Name</label>
            <input type="text" placeholder="" name='fname' required>
            <label>Last Name</label>
            <input type="text" placeholder="" name='lname' required>
            <label>Age</label>
            <input type="date" placeholder="" name='dob' required>
            <label>Email</label>
            <input type="email" placeholder="" name='email' required>
            <label>PassWord</label>
            <input type="password" placeholder="" name='password' required>
            <label>Confirm PassWord</label>
            <input type="password" placeholder="" name='conpassword' required>
            <input type="submit" value="Submit">
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && strlen($message) !== 0) {
                echo "<p>$message</p>";
        }
        ?>
        <p>By clicking the Sing up button , you agree to our <br>
        <a href="#">Terms and Condition</a> and <a href="#">Policy privacy</a>
        </p>
    </div>
    
</body>
</html>