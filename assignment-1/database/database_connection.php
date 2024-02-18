<?php

class DatabaseConnection {
    // Properties to store database connection details
    private $host;
    private $username;
    private $password;
    private $database;
    private PDO $connection; // PDO object for database connection

    // Constructor to initialize database connection
    public function __construct($host, $username, $password, $database) {
        // Initialize connection details
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        // Call the connect method to establish the connection
        $this->connect();
    }

    // Method to establish the database connection
    private function connect() {
        try {
            // Construct DSN for PDO connection
            $dsn = "mysql:host=".$this->host.";dbname=".$this->database."";
            // PDO options for error handling
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
            // Create PDO connection object
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Handle connection error
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Method to insert data into a table
    public function insert($tableName, $data) {
        // Construct SQL query for insertion
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";

        // Prepare and execute the query
        $statement = $this->connection->prepare($sql);
        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
        return $statement->execute();
    }

    // Method to insert data into a table and get the last inserted ID
    public function insertWithIdBack($tableName, $data) {
        // Construct SQL query for insertion
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $tableName ($columns) VALUES ($values)";

        // Prepare and execute the query
        $statement = $this->connection->prepare($sql);
        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        // Execute the query and return the last inserted ID
        if ($statement->execute()) {
            return $this->connection->lastInsertId();
        }
        // Return false if the execution failed
        return false;
    }
    
    // Method to select data from a table with optional condition
    public function select($tableName, $condition = "") {
        $sql = "SELECT * FROM $tableName";
        if ($condition) {
            $sql .= " WHERE $condition";
        }
        // echo"</br>";
        // echo $sql;
        // echo"</br>";
        $statement = $this->connection->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Method to select data from a table with multiple conditions
    public function selectWithConditions($tableName,string $baseConditions, $conditions = []) {
        // Build the base SQL query
        $sql = $baseConditions ?? "SELECT * FROM $tableName";

        // Add WHERE clause if conditions are provided
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $sql .= $this->buildWhereClause($conditions);
        }
    
        // Prepare and execute the query
        $statement = $this->connection->prepare($sql);
        $this->bindValues($statement, $conditions);
        $statement->execute();
    
        // Return the fetched rows
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Function to build the WHERE clause of the SQL query
    private function buildWhereClause($conditions) {
        $whereArrayConditions = [];
        $whereConditions = [];
    
        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                $whereArrayConditions[] = $this->buildNestedWhereClause($value);
            } else {
                $whereConditions[] = "$key {$value->getOperator()} :$key";
            }
        }
        if (!empty($whereArrayConditions)) {
            $whereConditions[] = "(".implode(" OR ", $whereArrayConditions).")";
        }
        return implode(" AND ",$whereConditions);
    }
    
    // Function to build nested WHERE clause for multidimensional arrays
    private function buildNestedWhereClause($nestedConditions) {
        $innerConditions = [];
    
        foreach ($nestedConditions as $key => $value) {
            $innerConditions[] = "$key {$value->getOperator()} :$key";
        }
       
        return "(" . implode(" AND ", $innerConditions) . ")";
    }
    
    // Function to bind parameter values to the prepared statement
    private function bindValues($statement, $conditions) {
        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $nestedKey => $nestedValue) {
                    $statement->bindValue(":$nestedKey", $nestedValue->condition());
                }
            } else {
                $statement->bindValue(":$key", $value->condition());
            }
        }
    }    

    // Method to update data in a table based on a condition
    public function update($tableName, $data, $condition) {
        $setClause = "";
        foreach ($data as $key => $value) {
            $setClause .= "$key = :$key, ";
        }
        $setClause = rtrim($setClause, ", ");

        $sql = "UPDATE $tableName SET $setClause WHERE $condition";

        $statement = $this->connection->prepare($sql);

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        return $statement->execute();
    }

    // Method to delete data from a table based on a condition
    public function delete($tableName, $condition) {
        $sql = "DELETE FROM $tableName WHERE $condition";

        $statement = $this->connection->prepare($sql);

        return $statement->execute();
    }

}
