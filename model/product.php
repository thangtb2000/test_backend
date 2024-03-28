<?php

class product
{
    private $conn;
    public $id;
    public $group_ID;
    public $name;
    public $price;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        try {
            $query = "SELECT Products.ID, Products.name, Products.price, Groups.group_name
            FROM Products
            INNER JOIN Groups ON Products.group_ID = Groups.ID";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            file_put_contents('error.log', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return [];
        }
    }

    public function show()
    {
        try {
            $query = "SELECT Products.ID, Products.name, Products.price, Groups.group_name
            FROM Products
            INNER JOIN Groups ON Products.group_ID = Groups.ID
            WHERE Products.ID = :productID";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':productID', $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name = $row['name'];
            $this->price = $row['price'];
            $this->group_ID = $row['group_name'];
        } catch (PDOException $e) {
            file_put_contents('error.log', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }


    public function create()
    {
        try {
            $query = "SELECT ID FROM Groups WHERE group_name = :group_name";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':group_name', $this->group_ID);
            $stmt->execute();
            $groupID = $stmt->fetchColumn();

            $query = "INSERT INTO Products SET name=:name, group_ID=:group_ID, price=:price";
            $stmt = $this->conn->prepare($query);
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->group_ID = htmlspecialchars(strip_tags($groupID));
            $this->price = htmlspecialchars(strip_tags($this->price));

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':group_ID', $this->group_ID);
            $stmt->bindParam(':price', $this->price);

            if ($stmt->execute()) {
                return true;
            } else {
                printf("Error %s.\n", $stmt->error);
                return false;
            }
        } catch (PDOException $e) {
            file_put_contents('error.log', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return false;
        }
    }


    public function update()
    {
        try {
            $query = "SELECT ID FROM Groups WHERE group_name = :group_name";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':group_name', $this->group_ID);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $groupID = $stmt->fetchColumn();

                $query = "UPDATE Products SET name=:name, group_ID=:group_ID, price=:price WHERE ID=:id";
                $stmt = $this->conn->prepare($query);
                //clean data
                $this->name = htmlspecialchars(strip_tags($this->name));
                $this->group_ID = htmlspecialchars(strip_tags($groupID));
                $this->price = htmlspecialchars(strip_tags($this->price));
                $this->id = htmlspecialchars(strip_tags($this->id));

                $stmt->bindParam(':name', $this->name);
                $stmt->bindParam(':group_ID', $this->group_ID);
                $stmt->bindParam(':price', $this->price);
                $stmt->bindParam(':id', $this->id);
            } else {
                printf("Error %s.\n", $stmt->error);
                return false;
            }
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            file_put_contents('error.log', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return false;
        }
    }


    public function delete()
    {
        try {
            $query = "DELETE FROM Products WHERE ID=:id";
            $stmt = $this->conn->prepare($query);
            //clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(':id', $this->id);

            if ($stmt->execute()) {
                return true;
            } else {
                printf("Error %s.\n", $stmt->error);
                return false;
            }
        } catch (PDOException $e) {
            file_put_contents('error.log', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return false;
        }
    }
}
