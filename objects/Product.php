<?php

namespace objects;

class Product
{
    private $conn;
    private $table = 'products';
    public $id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $category_name;
    public $created;
    public function __construct($db){
        $this->conn = $db;
    }
    function read(){
        $query = "SELECT c.name as category_name,p.id,p.name,
                         p.description,p.price,p.created,p.category_id
                    FROM $this->table p LEFt JOIN categories c ON 
                        p.category_id = c.id
                    ORDER BY p.created DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    function create()
    {
        $query = "INSERT INTO $this->table (name,description,price,created,category_id)
values (:name,:description,:price,:created,:category_id)";

    }
}