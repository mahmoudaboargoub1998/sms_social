<?php
require_once './smsphp/session_handler.php';
require_once './database/helper/conditions_type.php';
require_once './smsphp/post_sql.php';
require_once './smsphp/category_sql.php';
require_once './helper_function/query_search_builder.php';

SMSSessionHandler::start();
SMSSessionHandler::checkSession('id');

if (SMSSessionHandler::isValueSet('fname') && SMSSessionHandler::isValueSet('lname')) {
    $name = SMSSessionHandler::get('fname') . ' '.SMSSessionHandler::get('lname');
}
$categories = [];
$categoriesMessage = '';
$posts = [];
try{
    $db = new DatabaseConnection('localhost','root','','social_sms');
    $categoryManager = new CategorySql($db);
    $categories = $categoryManager->getAllCategories();
} catch(\Throwable){
    $categoriesMessage = "No categories ";
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

    
</head>
<body>
<form action="" method="get" class="search-form-bar">

    <nav>

        <div class="nav-left">
        <select id="search-category" name="cat_id">
                    <option value="">Select Category</option>
                    <?php
                    foreach ($categories as $category) {
                        echo "<option value='{$category->getId()}'>{$category->getName()}</option>";
                    }
                    ?>
                </select>
                <input type="date" name='created_at'>
        </div>
        <div class="nav-right">
            <div class="search-box">
                <image type="submit" src="image/search.png" alt="">
                <input type="text" placeholder="search ...." name='text'>

            </div>
            <div><button type="button" id="log-out-btn"><a href="./settings.php">Settings</a></button></div>
            <?php if(isset($name) && strlen($name)>0){ ?>
                <div><button type="button" id="log-out-btn"><a href="./smsphp/destroy_session.php">Logout</a></button></div>
            <?php }?>
            <div class="nav-user-icone online">
                <img src="image/profile.jpg" alt="profile">
            </div>
            <?php if(isset($name) && strlen($name)>0){?>
                <p><?=$name?></p>
            <?php }?>
        </div>

    </nav>
    </form>


    <div class="main-content">


            <!-- Display Search Results in a Table -->
        <div class="search-results">
                <?php

                    try{
                        $text = Conditions::pattren($_GET['text'] ?? '');
                        $cratedAt = Conditions::pattren($_GET['created_at'] ?? '');
                        $catId = Conditions::match($_GET['cat_id'] ?? '');
                        $db = $db ?? new DatabaseConnection('localhost','root','','social_sms');
                        $filtredSearchConditionsArry = QuerySearchBuilder::filterUndefinedTextFields([
                            ['fname' => $text],
                            ['lname' => $text],
                            ['title' => $text],
                            ['description' => $text],
                            'created_at' => $cratedAt,
                            'cat_id' => $catId
                        ]);
                        // echo "<pre>";
                        // echo print_r($filtredSearchConditionsArry);
                        // echo "</pre>";
                        $postSql = new PostSql($db);
                        $searchResults = $postSql->search($filtredSearchConditionsArry);
                      
                    }catch(\Throwable $e){
                        echo $e->getMessage();
                    }
                    if (!empty($searchResults)) {
                    ?>
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($searchResults as $result) {
                                echo '<tr>';
                                echo "<td>{$result['fname']} {$result['lname']}</td>";
                                // echo "<td>{$result['categroy_name']}</td>";
                                echo "<td>{$result['title']}</td>";
                                echo "<td>{$result['description']}</td>";
                                $cat_id = $result['cat_id'] ?? 0;
                                foreach ($categories as $key => $value) {
                                    if($value->getId()===$cat_id){
                                        $catValue = $value->getName();
                                    }
                                }
                                echo "<td>{$catValue}</td>";
                                echo "<td>{$result['created_at']}</td>";
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>No search results found.</p>
                <?php
                    }
                ?>
        </div>

    </div>
    <div class="right-sidebar"></div>
</body>
</html>

