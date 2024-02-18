<?php
require_once './smsphp/session_handler.php';
require_once './helper_function/helper.php';
require_once './database/database_connection.php';
require_once './smsphp/comment_model.php';
require_once './smsphp/post_model.php';
require_once './smsphp/user.php';
require_once './smsphp/comment_sql.php';
require_once './smsphp/post_sql.php';
require_once './smsphp/category_sql.php';
require_once './smsphp/user_sql.php';
require_once './helper_function/query_search_builder.php';
require_once './database/helper/conditions_type.php';

SMSSessionHandler::start();
SMSSessionHandler::checkSession('id');
$name = SMSSessionHandler::get("fname")." ".SMSSessionHandler::get("lname");
$message = "";
$categories=[];
$postsData =[];
$db = null;
if ($_SERVER["REQUEST_METHOD"] === "POST" && !areAllValuesEmpty($_POST)) {
    try {
        $db = new DatabaseConnection('localhost','root','','social_sms');

        $postSql = new PostSql($db);
        $postData = new PostModel();
        $postData->exportFromArray($_POST);
        $postData->setUserId(SMSSessionHandler::get('id'));
        if ($postSql->addPostWithCategory($postData,$_POST['new_category'])) {
            $message = "Post added successfully!";
        } else {
            $message = "Failed to add the post.";
        }
    } catch (\Throwable $th) {
        $message = "Error ".$th->getMessage().".";
    }   
}
try{
    $db = $db ?? new DatabaseConnection('localhost','root','','social_sms');
    $categoryManager = new CategorySql($db);
    $categories = $categoryManager->getAllCategories();
    $postManager = new PostSql($db);
    $postsData = $postManager->getPosts();
} catch(\Throwable $th){
    $message = "No categories";
    echo $th->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sms-social</title>
    <link rel="stylesheet" href="./style.css">
    <style>
        .small-textarea{
            height: 50px; 
            padding: 10px;
        }
        .comments-section {
        margin-top: 10px;
    }

    .comment {
        margin-bottom: 5px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .comment-user {
        font-weight: bold;
    }

    .comment-text {
        margin-left: 10px;
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
    <!-- containor -->
<div class="containor">
    <!-- left-sidebar -->
    <div class="left-sidebar">
<div class="emp-links">
    <a href="#"><img src="image/news.png">أخر الأخبار</a>
    <a href="#"><img src="image/people.png">الأصدقاء</a>
    <a href="#"><img src="image/human-resources.png">المجموعات</a>
    <a href="#"><img src="image/checkout.png">التسوق</a>
    <a href="#"><img src="image/help.png">المساعدة</a>
    <a href="#">....المزيد</a>
</div>
    </div>
    <!-- main-content -->
<div class="main-content">
<div class="formdiv">
        <h1>Add Post</h1>
 
        <form action="" method="POST">
            <label 
            class="option" for="">
            title 
            <input type="text" name="title" id="">

             </label>
            <br>
            <label for="" class="option"> Category
            <select name="cat_id" id="">
              <option value="">Select Category</option>

            <?php
                foreach ($categories as $category) {
                    echo "<option value='{$category->getId()}'>{$category->getName()}</option>";
                }
    
            ?>

             </select>
            </label>
            <input type="text" id="" name="new_category" placeholder="Specify Category">

            <textarea type="text" name="description" ></textarea>
            <label for="" class="option">Upload Media</label>
            <input type="file" name="imageupload" class="file-input">
            <ul>
                <li><input class="button2" type="submit" value="POST"></li>
                <li><a href="#"><button class="button2" type="reset">Clear</button></a></li>
            </ul>
        </form>
    </div>
    <div>
    <?php foreach ($postsData as $postData){ ?>
    <div class="post-card">
        <div class="post-title">Title: <?= $postData->getTitle() ?></div>
        <div class="post-description">Description: <?= $postData->getDescription() ?></div>
        <div class="post-details">Category: <?= $postData->category->getName() ?> | Username: <?= $postData->user->getFirstName() ?> <?= $postData->user->getLastName() ?> | Created At: <?= $postData->getCreatedAt() ?></div>
        
        <!-- Add Comment Form -->
        <form action="./add_comment.php" method="post">
            <input type="hidden" name="post_id" value="<?= $postData->getId() ?>">
            <textarea class="small-textarea" name="comment_text" rows="1" cols="50" placeholder="Enter your comment"></textarea>
            <input type="submit" value="Add Comment">
        </form>
           <!-- Comments section -->
           <div class="comments-section">
            <?php 
            $comments = $postData->getComments();
            foreach ($comments as $comment) : ?>
                <div class="comment">
                    <span class="comment-user"><?= $comment->getUser()->getFirstName() ?> <?= $comment->getUser()->getLastName() ?>:</span>
                    <span class="comment-text"><?= $comment->getText() ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php 
        } 
    ?>

</div>
<!-- right-sidebar -->
<div class="right-sidebar">

</div>
</div>
</body>
</html>