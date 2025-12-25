<?php
require_once "./core/database.php";

class MetaController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function categories()
    {
        $stmt = $this->db->query("SELECT * FROM categories");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function subcategories($category_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM subcategory WHERE category_id = ?");
        $stmt->execute([$category_id]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function allSubcategories()
{
    $stmt = $this->db->query("SELECT * FROM subcategory");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}


    public function colors()
    {
        $stmt = $this->db->query("SELECT * FROM colors");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function sizes()
    {
        $stmt = $this->db->query("SELECT * FROM sizes");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
