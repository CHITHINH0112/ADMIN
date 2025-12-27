<?php

require_once("./core/database.php");

class OnlyShipModel
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM shipping_providers ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($name, $phone, $price)
    {
        $sql = "INSERT INTO shipping_providers (name, phone, price)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $phone, $price]);
    }

    public function update($id, $name, $phone, $price)
    {
        $sql = "UPDATE shipping_providers 
                SET name = ?, phone = ?, price = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $phone, $price, $id]);
    }

    public function updatePrice($id, $price)
    {
        $sql = "UPDATE shipping_providers SET price = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$price, $id]);
    }

    // ⚠️ check order trước khi xóa
    public function hasOrders($id)
    {
        $sql = "SELECT COUNT(*) FROM orders WHERE shipping_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM shipping_providers WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
}

