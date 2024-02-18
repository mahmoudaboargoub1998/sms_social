<?php
require_once './smsphp/user.php';
require_once './smsphp/auth.php';
require './helper_function/helper.php';
require_once './smsphp/session_handler.php';
require_once './smsphp/user_sql.php';

SMSSessionHandler::start();
SMSSessionHandler::checkSession('id');

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && !areAllValuesEmpty($_POST)) {
     try {

     $auth = new UserSql(new DatabaseConnection('localhost','root','','social_sms'));
     
     $newUser = new User();
     $newUser->updateFromPostArray($_POST);
     $newUser->setId(SMSSessionHandler::get('id'));  

     $result = $auth->update($newUser);
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
    <title>settings</title>
    <link rel="stylesheet" href="./newAccount.css">
</head>
<body>
    <div class="Sing_Up">
        <h1>Settings </h1>
        <h4>update youre info.</h4> 
        <form action="" method="POST">
            <label>First Name</label>
            <input type="text" placeholder="" name='fname' value="<?=SMSSessionHandler::get('fname')?>" required>
            <label>Last Name</label>
            <input type="text" placeholder="" name='lname' value="<?=SMSSessionHandler::get('lname')?>" required>
            <label>Age</label>
            <input type="date" placeholder="" name='dob' value="<?=SMSSessionHandler::get('dob')?>" required>
            <label>Email</label>
            <input type="email" placeholder="" name='email' value="<?=SMSSessionHandler::get('email')?>" required>
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
