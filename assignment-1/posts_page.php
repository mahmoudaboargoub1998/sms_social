<?php
require_once './smsphp/session_handler.php';

require_once './database/database_connection.php';
require_once './smsphp/user.php';
require_once './smsphp/comment_model.php';
require_once './smsphp/post_model.php';

require_once './smsphp/comment_sql.php';
require_once './smsphp/post_sql.php';
require_once './smsphp/category_sql.php';
require_once './smsphp/user_sql.php';
require_once './helper_function/query_search_builder.php';
require_once './database/helper/conditions_type.php';

SMSSessionHandler::start();
SMSSessionHandler::checkSession('id');

if (SMSSessionHandler::isValueSet('fname') && SMSSessionHandler::isValueSet('lname')) {
    $name = SMSSessionHandler::get('fname') . ' '.SMSSessionHandler::get('lname');
}
$posts = [];
$message = "";
try {
    $db = new DatabaseConnection('localhost','root','','social_sms');
    $postSql = new PostSql($db);
    $posts = $postSql->getPosts("user_id=".SMSSessionHandler::get('id')."");
} catch (\Throwable $th) {
    $message = $th->getMessage();
    echo  $message;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <!-- Add your CSS stylesheets here -->
    <link rel="stylesheet" href="./style.css">
    <style>
       /* CSS styles for post cards */
       .container {
            width: 80%; /* Adjust the width of the container as needed */
            margin: 0 auto; /* Center the container horizontally */
        }

        .post-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post-card h2 {
            font-size: 1.5rem;
            margin-top: 0;
        }

        .post-card p {
            font-size: 1rem;
            line-height: 1.5;
        }

        .post-card p.category {
            font-style: italic;
            color: #666;
        }
.container h1{
    text-align: start!important;

}
.delete-btn{
    padding: 8px 16px;
}
    </style>
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
    <div class="nav-right">

        <div class="search-box">
          <img src="image/search.png" alt="">
          <input type="text" placeholder="search ....">
        </div>
        <div><button id="log-out-btn"><a href="./settings.php">Settings</a></button></div>

        <?php if(SMSSessionHandler::isValueSet('fname')&&SMSSessionHandler::isValueSet('lname')){ ?>
        <div><button id="log-out-btn"><a href="./smsphp/destroy_session.php">Logout</a></button></div>
        <?php }?>
        <div class="nav-user-icone online">
            <img src="image/profile.jpg" alt="profile">
        </div>
        <?php if(strlen($name)!==0){?>
            <p><?=$name?></p>
            <?php }?>
    </div>
    </nav>
    <div class="container">
    <br>
    <h1>Your Posts</h1>
    <br>

    <?php 
        if(!empty($posts)){
            foreach ($posts as $postData) {
                
    ?>
                <div class="post-card">
                    <h2><?= $postData->getTitle() ?></h2>
                    <p><?= $postData->getDescription() ?></p>
                    <p>Category: <?= $postData->category->getName() ?></p>
                    <!-- Add delete button with confirmation prompt -->
                    <button type="button" class="delete-btn" value="<?= $postData->getId() ?>" >Delete</button>
                </div>
    <?php 
            } 
        }else{
    ?>
        <p>No posts Found.</p>
    <?php
        }
    ?>
    </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                // delete button element
                const button = [...document.getElementsByClassName('delete-btn')];
                button.forEach((e)=>{
                    e.addEventListener( 
                    'click', function () {
                    // Ask for confirmation before deleting the post
                    var postId = e.value;

                    if (confirm(`Are you sure you want to delete this post with id ${postId} ?`)) {
                        // Redirect to the delete script with the post ID as a parameter
                        if (postId) {
                            window.location.href = './delete_post.php?postId=' + postId;
                        }
                    }
                });
                });
            });
        </script>
</body>
</html>
