<?php
require_once("./app/Service/ProductService.php");

class ProductController
{
    private $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    // GET ALL
    public function index()
    {
        echo json_encode($this->service->getAll());
    }

    // GET BY ID
    public function getid($id)
    {
        echo json_encode($this->service->getById($id));
    }

    // CREATE (JSON ONLY)
   public function create()
{
    echo json_encode(
        $this->service->create($_POST, $_FILES)
    );
}

    // UPDATE (JSON ONLY)
    // public function update()
    // {
    //     $body = json_decode(file_get_contents("php://input"), true);
    //     echo json_encode($this->service->update($body));
    // }

    // DELETE
    public function delete($id)
    {
        echo json_encode($this->service->delete($id));
    }

    //SEARCH 
    public function search()
{
    $keyword = $_GET['keyword'] ?? '';
    echo json_encode($this->service->search($keyword));
}


public function update()
{
    echo json_encode(
        $this->service->update($_POST, $_FILES)
    );
}
}
