
<?php
require_once './database/database_connection.php';
class Auth {
    private DatabaseConnection $database;

    public function __construct(DatabaseConnection $database) {
        $this->database = $database;
    }

    public function signup(User $user) {
        // Check if the username or email already exists
        if ($this->userExists($user->getEmail())) {
            return false; // User already exists
        }

        // Hash the password before storing it
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);


        return $this->database->insertWithIdBack('users', $user->getProprtyArryWithHashedPassword($hashedPassword));
    }

    public function login(User $loggedUser) {
        // Check if the username or email exists
        $user = $this->database->select('users', "email = '" . $loggedUser->getEmail() . "'");
        if (empty($user)) {
            return null; // User not found
        }
        print_r($user[0]);
        echo $loggedUser->getPassword();
        echo $user[0]['password'];
        // Verify the password
        $hashedPassword = $user[0]['password'];
        if (password_verify($loggedUser->getPassword(), $hashedPassword)) {
          
             $loggedUser->updateFromPostArray($user[0]); // Login successful
             return $loggedUser;
        } else {
            return null; // Incorrect password
        }
    }

    public function userExists( $email) {
        // Check if the username or email already exists in the database
        $existingUser = $this->database->select('users', "email = '$email'");
        return !empty($existingUser);
    }
}
