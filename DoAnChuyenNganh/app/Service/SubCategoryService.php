<?php
/*require_once("./app/Model/SubcategoryModel.php");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

class SubcategoryService
{
    private $subcategoryModel;

    public function __construct()
    {
        $this->subcategoryModel = new Subcategory();
    }

   
    public function getAll()
    {
        return $this->subcategoryModel->getAll();
    }

   
    public function getById($id)
    {
        $subcategory = $this->subcategoryModel->getById($id);

        if (!$subcategory) {
            return [
                "status" => "error",
                "message" => "Subcategory not found"
            ];
        }

        return [
            "status" => "success",
            "data" => $subcategory
        ];
    }

    
    public function create($name, $category_id)
    {
        AdminMiddleware::requireAdmin();

        $ok = $this->subcategoryModel->create($name, $category_id);

        return [
            "status" => $ok ? "success" : "error",
            "message" => $ok ? "Subcategory created successfully" : "Failed to create subcategory"
        ];
    }

    
    public function update($id, $name, $category_id)
    {
        AdminMiddleware::requireAdmin();

        $ok = $this->subcategoryModel->update($id, $name, $category_id);

        return [
            "status" => $ok ? "success" : "error",
            "message" => $ok ? "Subcategory updated successfully" : "Failed to update subcategory"
        ];
    }

   
    public function delete($id)
    {
        AdminMiddleware::requireAdmin();

        $ok = $this->subcategoryModel->delete($id);

        return [
            "status" => $ok ? "success" : "error",
            "message" => $ok ? "Subcategory deleted successfully" : "Failed to delete subcategory"
        ];
    }

   
    public function getByCategory($category_id)
    {
        $subcategories = array_filter($this->subcategoryModel->getAll(), function ($subcat) use ($category_id) {
            return $subcat['category_id'] == $category_id;
        });

        return [
            "status" => "success",
            "data" => array_values($subcategories)
        ];
    }
}
*/

require_once("./app/Model/SubcategoryModel.php");
require_once("./app/Middleware/AdminMiddleware.php");

class SubcategoryService
{
    private $model;

    public function __construct()
    {
        $this->model = new SubcategoryModel();
    }

    public function getAll()
    {
        return $this->model->getAll();
    }

    public function getById($id)
    {
        $data = $this->model->getById($id);

        if (!$data) {
            return ["success" => false, "message" => "Not found"];
        }

        return ["success" => true, "data" => $data];
    }

    public function create($name, $category_id)
    {
        AdminMiddleware::requireAdmin();

        if (!$name || !$category_id) {
            return ["success" => false, "message" => "Missing data"];
        }

        return [
            "success" => $this->model->create($name, $category_id)
        ];
    }

    public function update($id, $name, $category_id)
    {
        AdminMiddleware::requireAdmin();

        return [
            "success" => $this->model->update($id, $name, $category_id)
        ];
    }

    public function delete($id)
    {
        AdminMiddleware::requireAdmin();

        return [
            "success" => $this->model->delete($id)
        ];
    }

    public function getByCategory($category_id)
    {
        return $this->model->getByCategory($category_id);
    }
}
