<?php
/*require_once("./core/database.php");
class Subcategory
{
    private $conn;
    private $table = "subcategory";

    public $id;
    public $name;
    public $category_id;

    public function __construct()
    {

        $db = new Database();
        $this->conn = $db->connect();
    }

    // Lấy tất cả subcategory
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY category_id, id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy subcategory theo ID
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm mới subcategory
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (name, category_id) VALUES (:name, :category_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':category_id', $this->category_id);
        return $stmt->execute();
    }

    // Cập nhật subcategory
    public function update()
    {
        $query = "UPDATE " . $this->table . " SET name = :name, category_id = :category_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Xóa subcategory
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
*/


require_once("./core/database.php");

class SubcategoryModel
{
    private $conn;
    private $table = "subcategory";

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function getAll()
    {
        $sql = "
            SELECT s.*, c.name AS category_name
            FROM subcategory s
            JOIN categories c ON s.category_id = c.id
            ORDER BY c.id, s.id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM subcategory WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $category_id)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO subcategory (name, category_id) VALUES (?, ?)"
        );
        return $stmt->execute([$name, $category_id]);
    }

    public function update($id, $name, $category_id)
    {
        $stmt = $this->conn->prepare(
            "UPDATE subcategory SET name = ?, category_id = ? WHERE id = ?"
        );
        return $stmt->execute([$name, $category_id, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM subcategory WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    public function getByCategory($category_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM subcategory WHERE category_id = ?"
        );
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
