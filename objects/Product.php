<?php

namespace objects;

use PDO;

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

    public function readOne()
    {
        $query = "SELECT
            c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
        FROM
            " . $this->table . " p
            LEFT JOIN
                categories c
                    ON p.category_id = c.id
        WHERE
            p.id = ?
        LIMIT
            0,1";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // привязываем id товара, который будет получен
        $stmt->bindParam(1, $this->id);

        // выполняем запрос
        $stmt->execute();

        // получаем извлеченную строку
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // установим значения свойств объекта
        $this->name = $row["name"];
        $this->price = $row["price"];
        $this->description = $row["description"];
        $this->category_id = $row["category_id"];
        $this->category_name = $row["category_name"];
    }

    public function update()
    {
        // запрос для обновления записи (товара)
        $query = "UPDATE
            " . $this->table. "
        SET
            name = :name,
            price = :price,
            description = :description,
            category_id = :category_id
        WHERE
            id = :id";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // привязываем значения
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":id", $this->id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        // запрос для удаления записи (товара)
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";

        // подготовка запроса
        $stmt = $this->conn->prepare($query);

        // очистка
        $this->id = htmlspecialchars(strip_tags($this->id));

        // привязываем id записи для удаления
        $stmt->bindParam(1, $this->id);

        // выполняем запрос
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}