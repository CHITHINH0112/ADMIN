<?php
require_once("./app/Service/CategoryService.php");
require_once("./app/Middleware/AdminMiddleware.php");
// Cho phép frontend ở localhost:5173 gọi API
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


class CategoryController
{
    private $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function index()
    {
        echo json_encode($this->categoryService->getAll());
    }

    public function getById($id)
    {
        echo json_encode($this->categoryService->getById($id));
    }

    public function create($name, $description)
    {

        AdminMiddleware::requireAdmin();
        echo json_encode($this->categoryService->create($name, $description));
    }

    public function update($id, $name, $description)
    {
        echo json_encode($this->categoryService->update($id, $name, $description));
    }

    public function delete($id)
    {
        echo json_encode($this->categoryService->delete($id));
    }
}
