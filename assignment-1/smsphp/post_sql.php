<?php
class PostSql {
    private $db;

    public function __construct(DatabaseConnection $db) {
        $this->db = $db;
    }

    public function addPost(PostModel $postData) {
        $data = $postData->toArray();
        return $this->db->insert('post', $data);
    }
    public function addPostWithCategory(PostModel $postData, $newCategoryName = null) {
  
        $catId = $postData->getCatId();

        if ($newCategoryName !== null) {
            $categorySql = new CategorySql($this->db);
            $categoryData = $categorySql->getCategoryByName($newCategoryName);
       
            if ($categoryData === null) {
                // If category doesn't exist, add a new category
                if ($categorySql->addCategory($newCategoryName)) {
                    $categoryData = $categorySql->getCategoryByName($newCategoryName);
                } else {
                    return false;
                }
            }

            $catId = $categoryData->getId();
        }

        if ($catId !== null) {
            $postData->setCatId($catId);
            return $this->addPost($postData);
        }

        return false;
    }
    public function updatePost(PostModel $postData, $postId) {
        $data = $postData->toArray();
        $condition = "id = $postId";
        return $this->db->update('post', $data, $condition);
    }

    public function deletePost($postId) {
        $condition = "id = $postId";
        return $this->db->delete('post', $condition);
    }

    public function getPosts($condition='') {
        $postsData = [];
        $posts = $this->db->select('post',$condition);
        // echo "</br>";
        // echo "$condition";
        // echo "</br>";
        // echo print_r($posts);
        // echo "</br>";

        $catSql = new CategorySql($this->db);
        $userSql = new UserSql($this->db);
        $commentSql = new CommentSql($this->db);
        // echo "</br>";
        // echo "user and category sql is created.";
        // echo "</br>";
        foreach ($posts as $post) {

            $postData = new PostModel();
            $postData->exportFromArray($post);
            $cat = $catSql->getCategoryId($postData->getCatId());
            $postData->user = $userSql->getUserById($postData->getUserId());
            $postData->category =  $cat;
            // echo "</br>";
            // echo "before get comments";
            // echo "</br>";
            $comments = $commentSql->getCommentsByPostId($postData->getId());
            // echo "</br>";
            // echo "after get comments";
            // echo "</br>";
            $postData->setComments($comments);
            $postsData[] = $postData;
        }

        return $postsData;
    }

    public function search(array $searchConditions) {
        return $this->db->selectWithConditions(
            'post',
            "SELECT p.*, u.fname, u.lname FROM post p JOIN users u ON p.user_id = u.id",
            $searchConditions
        );
    }
}
