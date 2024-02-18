<?php
require_once './smsphp/session_handler.php';
require_once './database/database_connection.php';
require_once './smsphp/comment_sql.php';
require_once './smsphp/comment_model.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Start the session
    SMSSessionHandler::start();
    // Check session
    SMSSessionHandler::checkSession('id');

    // Get the comment data from the POST request
    $commentData = [
        'post_id' => $_POST['post_id'],
        'user_id' => SMSSessionHandler::get('id'),
        'comment_text' => $_POST['comment_text']
    ];

    try {
        // Create a new DatabaseConnection instance
        $db = new DatabaseConnection('localhost', 'root', '', 'social_sms');
        // Create a new CommentSql instance
        $commentSql = new CommentSql($db);
        
        // Create a new CommentModel instance
        $commentModel = new CommentModel();
        // Set the comment data to the model
        $commentModel->setDataFromArray($commentData);

        // Add the comment to the post
        $added = $commentSql->addComment($commentModel);

        if ($added) {
            // Comment added successfully
            // Redirect back to the previous page with success message
            header("Location: {$_SERVER['HTTP_REFERER']}?message=Comment added successfully");
            exit;
        } else {
            // Failed to add the comment
            // Redirect back to the previous page with error message
            header("Location: {$_SERVER['HTTP_REFERER']}?message=Failed to add comment");
            exit;
        }
    } catch (\Throwable $th) {
        // Handle any exceptions
        // Redirect back to the previous page with error message
        header("Location: {$_SERVER['HTTP_REFERER']}?message=" . urlencode($th->getMessage()));
        exit;
    }
} else {
    // If the request is not a POST request
    // Redirect back to the previous page with error message
    header("Location: {$_SERVER['HTTP_REFERER']}?message=Invalid request method");
    exit;
}
?>
