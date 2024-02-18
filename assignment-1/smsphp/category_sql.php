
<?php
require_once './smsphp/category_model.php';
class CategorySql {
    private $db;

    public function __construct(DatabaseConnection $db) {
        $this->db = $db;
    }

    public function addCategory($categoryName) {
        $categoryData = ['name' => $categoryName];
        return $this->db->insert('category', $categoryData);
    }

    public function getCategoryByName($categoryName) {

        $condition = "name = '".$categoryName."'";
        $category = $this->db->select('category', $condition);
        if (!empty($category)) {
            $categoryData = new CategoryModel();
            $categoryData->setId($category[0]['id']);
            $categoryData->setName($category[0]['name']);
            return $categoryData;
        }
       return null;


    }
    public function getCategoryId($catid) {

        $condition = "id = '".$catid."'";
        $category = $this->db->select('category', $condition);
        if (!empty($category)) {
            $categoryData = new CategoryModel();
            $categoryData->setId($category[0]['id']);
            $categoryData->setName($category[0]['name']);
            return $categoryData;
        }
       return null;


    }
    public function getAllCategories() {
        $categories = $this->db->select('category');

        $categoryList = [];

        foreach ($categories as $category) {
            $categoryData = new CategoryModel();
            $categoryData->setId($category['id']);
            $categoryData->setName($category['name']);
            $categoryList[] = $categoryData;
        }

        return $categoryList;
    }
}
