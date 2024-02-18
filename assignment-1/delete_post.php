<?php
require_once './smsphp/session_handler.php';
require_once './database/database_connection.php';
require_once './smsphp/post_sql.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if the post ID is set in the $_GET array
    if (isset($_GET['postId'])) {
        // Start the session
        SMSSessionHandler::start();
        // Check session
        SMSSessionHandler::checkSession('id');

        // Get the post ID from the $_GET array
        $postId = $_GET['postId'];

        try {
            // Create a new DatabaseConnection instance
            $db = new DatabaseConnection('localhost', 'root', '', 'social_sms');
            // Create a new PostSql instance
            $postSql = new PostSql($db);
            // Attempt to delete the post
            $deleted = $postSql->deletePost($postId);

            if ($deleted) {
                // Post deleted successfully
                // Redirect back to the previous page with success message
                header("Location: {$_SERVER['HTTP_REFERER']}?message=Post deleted successfully");
                exit;
            } else {
                // Failed to delete the post
                // Redirect back to the previous page with error message
                header("Location: {$_SERVER['HTTP_REFERER']}?message=Failed to delete post");
                exit;
            }
        } catch (\Throwable $th) {
            // Handle any exceptions
            // Redirect back to the previous page with error message
            header("Location: {$_SERVER['HTTP_REFERER']}?message=" . urlencode($th->getMessage()));
            exit;
        }
    } else {
        // If post ID is not set in $_GET array
        // Redirect back to the previous page with error message
        header("Location: {$_SERVER['HTTP_REFERER']}?message=Post ID is not set");
        exit;
    }
} else {
    // If the request is not a GET request
    // Redirect back to the previous page with error message
    header("Location: {$_SERVER['HTTP_REFERER']}?message=Invalid request method");
    exit;
}
