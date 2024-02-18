<?php
require_once './smsphp/session_handler.php';

SMSSessionHandler::start();

 $name = SMSSessionHandler::get("fname")." ".SMSSessionHandler::get("lname");
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sms-social</title>
    <link rel="stylesheet" href="./home.css">
</head>
<body>
    <nav>
    <div class="nav-left">
        <img src="image/smartphone.png" alt="logo" class="logo">
        <ul>
            <li><img src="image/bell-ring.png" alt=""></li>
            <li><img src="image/chatting.png" alt=""></li>
            <li><img src="image/play.png" alt=""></li>
        </ul>
    </div>
     </div>
     <div class="nav-right">
        <?php if(strlen($name)!==0){?>
            <p><?=$name?></p>
            <?php }?>
    </div>
    </nav>
    <!-- containor -->
<div class="containor">
    <!-- left-sidebar -->
    <div class="left-sidebar">
</div>
    
    <!-- main-content -->
<div class="main-content">
    <div class="bt">
        <a href="login.php" target="_blank"><button class="button">LogIn</button></a> 
        <a href="newAccount.php" target="_blank"><button class="button">Sing Up</button></a>  
        <a href="index.php" target="_blank"><button class="button">Post</button></a>    
      </div>
</div>
<!-- right-sidebar -->
<div class="right-sidebar">

</div>
</div>
</div>
</body>
</html>