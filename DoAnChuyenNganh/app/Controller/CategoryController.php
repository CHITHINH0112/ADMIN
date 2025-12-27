<?php
 /*require_once("./app/Service/CategoryService.php");
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

*/



require_once "./app/Service/CategoryService.php";
require_once "./app/Middleware/AdminMiddleware.php";

class CategoryController
{
    private $service;

    public function __construct()
    {
        $this->service = new CategoryService();
    }

    // GET category/getAll
    public function getAll()
    {
        echo json_encode($this->service->getAll());
    }

    // GET category/getById/{id}
    public function getById($id)
    {
        echo json_encode($this->service->getById($id));
    }

    // POST category/create
    public function create()
    {
        AdminMiddleware::requireAdmin();
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['name'])) {
            echo json_encode([
                "success" => false,
                "message" => "Missing name"
            ]);
            return;
        }

        echo json_encode(
            $this->service->create(
                $data['name'],
                $data['description'] ?? null
            )
        );
    }

    // PUT category/update/{id}
    public function update($id)
    {
            AdminMiddleware::requireAdmin(); // 👈 DÒNG QUAN TRỌNG

        $data = json_decode(file_get_contents("php://input"), true);

        echo json_encode(
            $this->service->update(
                $id,
                $data['name'],
                $data['description'] ?? null
            )
        );
    }

    // DELETE category/delete/{id}
    public function delete($id)
    {
            AdminMiddleware::requireAdmin(); // 👈 DÒNG QUAN TRỌNG

        echo json_encode(
            $this->service->delete($id)
        );
    }

    // POST category/updateOrder
    public function updateOrder()
    {
            AdminMiddleware::requireAdmin(); // 👈 DÒNG QUAN TRỌNG

        $data = json_decode(file_get_contents("php://input"), true);

        echo json_encode(
            $this->service->updateOrder($data)
        );
    }
}
