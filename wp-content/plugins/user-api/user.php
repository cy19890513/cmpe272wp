<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "58f_users";
 
    // object properties
    public $id;
    public $name;
    public $email;
    //public $price;
    //public $category_id;
    //public $category_name;
    //public $created;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read products
    function read(){
     
        // select all query
        $query = "SELECT
                    u.display_name, u.user_email
                FROM
                    " . $this->table_name . " u";
                    
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
}