<?php
require_once './smsphp/user.php';
require './helper_function/helper.php';
require_once './smsphp/auth.php';
require_once './smsphp/session_handler.php';
SMSSessionHandler::start();
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !areAllValuesEmpty($_POST)) {
    try {
        $auth = new Auth(new DatabaseConnection('localhost','root','','social_sms'));
        $loggedUser = new User();
        $loggedUser->updateFromPostArray($_POST);
        $result = $auth->login($loggedUser);
        if ($result->getId() !== null) {
            SMSSessionHandler::set('id',$result->getId());
            SMSSessionHandler::set('fname',$result->getFirstName());
            SMSSessionHandler::set('lname',$result->getLastName());
            SMSSessionHandler::set('email',$result->getEmail());
            SMSSessionHandler::set('dob',$result->getDOB());
            header("Location: index.php");
            exit();
        }else{
            $message = "User is not found.";
        }
    } catch (\Throwable $th) {
        $message = $th->getMessage();
    }
}else{
    $message = 'NO data input';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="./login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrapper">
        <form action="" method ="post">
            <h1>Social Sms</h1>
            <div class="input-box">
                <input type="text" placeholder="email" name="email">
                <box-icon type='solid' name='user'></box-icon>
            </div>
            <div class="input-box">
                <input type="password" placeholder="password" name="password">
                <box-icon type='solid' name='lock-alt'></box-icon>
            </div>
            <div class="remember-forget">
                <label> <input type="checkbox">Remember me</label>
                <a href="#">forget password?</a>
            </div>
            <button type="submit" class="btn">login</button>
            <div class="register-link">
                <p>Don't have an account? 
                    <a href="./newAccount.php">Register</a></p>
            </div>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && strlen($message) !== 0) {
                echo "<p>$message</p>";
        }
        ?>
    </div>
</body>
</html>