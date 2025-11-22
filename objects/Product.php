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
        $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->created = htmlspecialchars(strip_tags($this->created));
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":created", $this->created);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
}