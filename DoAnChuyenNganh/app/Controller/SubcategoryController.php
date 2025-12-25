<?php
require_once("./app/Service/SubCategoryService.php");
// Cho phép frontend ở localhost:5173 gọi API
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


class SubcategoryController
{
    private $subcategoryService;

    public function __construct()
    {
        $this->subcategoryService = new SubcategoryService();
    }

    // Lấy tất cả subcategory
    public function index()
    {
        echo json_encode($this->subcategoryService->getAll());
    }

    // Lấy subcategory theo ID
    public function getById($id)
    {
        echo json_encode($this->subcategoryService->getById($id));
    }

    // Lấy subcategory theo category_id
    public function getByCategory($category_id)
    {
        echo json_encode($this->subcategoryService->getByCategory($category_id));
    }

    // Thêm subcategory mới
    public function create()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $name = $body['name'] ?? null;
        $category_id = $body['category_id'] ?? null;

        echo json_encode($this->subcategoryService->create($name, $category_id));
    }

    // Cập nhật subcategory
    public function update($id)
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $name = $body['name'] ?? null;
        $category_id = $body['category_id'] ?? null;
        session_write_close();

        echo json_encode($this->subcategoryService->update($id, $name, $category_id));
    }

    // Xóa subcategory
    public function delete($id)
    {
        echo json_encode($this->subcategoryService->delete($id));
    }
}
