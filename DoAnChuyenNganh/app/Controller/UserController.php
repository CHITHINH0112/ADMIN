<?php
require_once "./app/Service/UserService.php";
require_once "./app/Middleware/AuthMiddleware.php";
require_once "./app/Middleware/AdminMiddleware.php";
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


class UserController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index()
    {
        AdminMiddleware::requireAdmin();
        // echo json_encode($this->userService->getAll());

        echo json_encode($this->userService->getUsersWithLastOrder());

    }

    public function id($id)
    {
        AdminMiddleware::requireAdmin();
        echo json_encode($this->userService->getById($id));
    }

    public function update($id)
    {
        AdminMiddleware::requireAdmin();

        $body = json_decode(file_get_contents("php://input"), true);
        $ok = $this->userService->update($id, $body);

        echo json_encode(["success" => $ok]);
    }

    public function delete($id)
    {
        AdminMiddleware::requireAdmin();
        $ok = $this->userService->delete($id);

        echo json_encode(["success" => $ok]);
    }

public function status($id)
{
    AdminMiddleware::requireAdmin();

    $body = json_decode(file_get_contents("php://input"), true);
    $ok = $this->userService->changeStatus($id, $body["status"]);

    echo json_encode(["success" => $ok]);
}


public function resetPassword($id)
{
    AdminMiddleware::requireAdmin();
    $ok = $this->userService->resetPassword($id);
    echo json_encode(["success" => $ok]);
}



}
