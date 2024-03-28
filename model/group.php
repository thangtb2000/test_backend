<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class group
{
    private $conn;
    public $id;
    public $group_name;
    public $title;
    public $content;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        try {
            $query = "SELECT * FROM Groups";
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
            $query = "SELECT * FROM Groups WHERE ID=? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->group_name = $row['group_name'];
            $this->title = $row['title'];
            $this->content = $row['content'];
        } catch (PDOException $e) {
            file_put_contents('error.log', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }

    public function create()
    {
        try {

            $query = "INSERT INTO Groups SET group_name=:group_name, title=:title, content=:content";
            $stmt = $this->conn->prepare($query);
            //clean data
            $this->group_name = htmlspecialchars(strip_tags($this->group_name));
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->content = htmlspecialchars(strip_tags($this->content));

            $stmt->bindParam(':group_name', $this->group_name);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':content', $this->content);
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
            $query = "UPDATE Groups SET group_name=:group_name, title=:title, content=:content WHERE ID=:id";
            $stmt = $this->conn->prepare($query);
            //clean data
            $this->group_name = htmlspecialchars(strip_tags($this->group_name));
            $this->title = htmlspecialchars(strip_tags($this->title));
            $this->content = htmlspecialchars(strip_tags($this->content));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':group_name', $this->group_name);
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':content', $this->content);
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

    public function delete()
    {
        try {
            $query = "DELETE FROM Groups WHERE ID=:id";
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
