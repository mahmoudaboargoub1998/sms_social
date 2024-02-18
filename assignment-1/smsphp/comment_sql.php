<?php
class CommentSql {
    private $db;

    public function __construct(DatabaseConnection $db) {
        $this->db = $db;
    }

    // Method to add a comment to a post
    public function addComment(CommentModel $comment) {
        // Convert the comment object to an array
        $commentData = $comment->toArray();
        // Insert the comment into the comments table
        return $this->db->insert('comments', $commentData);
    }

    // Method to get comments by post ID
    public function getCommentsByPostId($postId) {
        $sql = "SELECT c.*, u.fname, u.lname 
                FROM comments c 
                JOIN users u ON c.user_id = u.id";
        $conditions =QuerySearchBuilder::filterUndefinedTextFields(['post_id' => $postId]);
        $commentsData = $this->db->selectWithConditions('comments', $sql, $conditions);
        
        // Initialize an array to store CommentModel objects
        $comments = [];

        // Iterate through each row of comments data
        foreach ($commentsData as $commentData) {
            // Create a new CommentModel object and set its data from the row
            $comment = new CommentModel();
            $comment->setDataFromArray($commentData);
            // Create a new User object and set its data from the row
            $user = new User();
            $user->setFirstName($commentData['fname']);
            $user->setLastName($commentData['lname']);
            // Set the User object as the user property of the CommentModel object
            $comment->setUser($user);
            // Add the CommentModel object to the array
            $comments[] = $comment;
        }
        // Return the array of CommentModel objects
        return $comments;
    }
}
