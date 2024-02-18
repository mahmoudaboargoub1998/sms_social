<?php
class UserSql {
    private $db;

    public function __construct(DatabaseConnection $db) {
        $this->db = $db;
    }

    public function getUserById($id) {
        $condition = "id = '" . $id."'";

        $userResult = $this->db->select('users', $condition);

        if (!empty($userResult)) {
 
            $user = new User();
            $user->updateFromPostArray($userResult[0]);
         
    
            return $user;
        }
        return null;
    }

    public function update(User $user) {
        $condition = 'id = ' . $user->getId();
        return $this->db->update('users', $user->toArray(), $condition);
    }

    public function delete($Id) {
        $condition = 'id = ' . $Id;
        return $this->db->delete('users', $condition);
    }

}


