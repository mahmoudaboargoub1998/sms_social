    <?php
    class PostModel {
        private $id;
        private $title;
        private $description;
        private $catId;
        private $userId;
        private $createdAt;  // New property for created_at timestamp
        public CategoryModel $category;
        public User $user;
        private $comments;
        public function getId() {
            return $this->id;
        }
        public function getTitle() {
            return $this->title;
        }

        public function setTitle($title) {
            $this->title = $title;
        }
        public function getComments() {
            return $this->comments;
        }

        public function setComments($comments) {
            $this->comments = $comments;
        }

        public function getDescription() {
            return $this->description;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function getCatId() {
            return $this->catId;
        }

        public function setCatId($catId) {
            $this->catId = $catId;
        }

        public function getUserId() {
            return $this->userId;
        }
        public function getCreatedAt() {
            return $this->createdAt;
        }

        public function setUserId($userId) {
            $this->userId = $userId;
        }
        public function exportFromArray($postData) {
            $this->id = $postData['id'] ?? 0;
            $this->title = $postData['title'] ?? '';
            $this->description = $postData['description'] ?? '';
            $this->catId = $postData['cat_id'] ?? 0;
            $this->userId = $postData['user_id'] ?? 0;
            $this->createdAt = date('Y-m-d H:i:s');
        }

        public function toArray() {
            return [
                'title' => $this->title,
                'description' => $this->description,
                'cat_id' => $this->catId,
                'user_id' => $this->userId,
                
            ];
        }
    }
