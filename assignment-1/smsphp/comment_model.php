<?php
class CommentModel {
    private $id;
    private $postId;
    private $userId;
    private $text;
    private $createdAt;
    private $user;
    public function __construct($id=null, $postId=null, $userId=null, $text=null, $createdAt=null) {
        $this->id = $id;
        $this->postId = $postId;
        $this->userId = $userId;
        $this->text = $text;
        $this->createdAt = $createdAt;
    }

    public function getUser():User {
        return $this->user;
    }
    public function setUser(User $user) {
        $this->user = $user;
    }
    public function getId() {
        return $this->id;
    }

    public function getPostId() {
        return $this->postId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setDataFromArray($comment) {
        $this->id = $comment['comment_id']??$this->id;
        $this->postId = $comment['post_id']??$this->postId;
        $this->userId = $comment['user_id']??$this->userId;
        $this->text = $comment['comment_text']??$this->text;
        $this->createdAt = $comment['created_at']??$this->createdAt;
    }

    public function toArray() {
        return [
            'post_id' => $this->postId,
            'user_id' => $this->userId,
            'comment_text' => $this->text,
        ];
    }
}

