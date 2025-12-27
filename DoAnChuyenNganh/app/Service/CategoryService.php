<?php
/*require_once("./app/Model/CategoryModel.php");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

class CategoryService
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    // Lấy toàn bộ danh mục
    public function getAll()
    {
        return $this->categoryModel->getAll();
    }

    // Lấy 1 danh mục theo ID
    public function getById($id)
    {
        $category = $this->categoryModel->getById($id);

        if (!$category) {
            return [
                "status" => "error",
                "message" => "Category not found"
            ];
        }

        return [
            "status" => "success",
            "data" => $category
        ];
    }

    // Tạo danh mục
    public function create($name, $description)
    {
        AdminMiddleware::requireAdmin();
        $ok = $this->categoryModel->create($name, $description);

        return [
            "status" => $ok ? "success" : "error",
            "message" => $ok ? "Category created" : "Create failed"
        ];
    }

    // Cập nhật danh mục
    public function update($id, $name, $description)
    {

        $ok = $this->categoryModel->update($id, $name, $description);

        return [
            "status" => $ok ? "success" : "error",
            "message" => $ok ? "Category updated" : "Update failed"
        ];
    }

    // Xóa danh mục
    public function delete($id)
    {
        AdminMiddleware::requireAdmin();
        $ok = $this->categoryModel->delete($id);

        return [
            "status" => $ok ? "success" : "error",
            "message" => $ok ? "Category deleted" : "Delete failed"
        ];
    }
}





*/

require_once __DIR__ . "/../Model/CategoryModel.php";

class CategoryService
{
    private $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    public function getAll()
    {
        return $this->model->getAll();
    }

    public function getById($id)
    {
        return $this->model->getById($id);
    }

    public function create($name, $description)
    {
        $this->model->create($name, $description);
        return ["success" => true];
    }

    public function update($id, $name, $description)
    {
        $this->model->update($id, $name, $description);
        return ["success" => true];
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return ["success" => true];
    }

    public function updateOrder($orders)
    {
        $this->model->updateOrder($orders);
        return ["success" => true];
    }
}
