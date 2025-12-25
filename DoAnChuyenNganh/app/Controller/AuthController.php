<?php
require_once "app/Service/AuthService.php";

class AuthController
{
    private $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function login()
    {
        $body = json_decode(file_get_contents("php://input"), true);

        $username = $body['username'] ?? '';
        $password = $body['password'] ?? '';

        $res = $this->service->login($username, $password);
        echo json_encode($res);
        exit;
    }
    // mới thêm
    public function logout()
{
    session_destroy();
    echo json_encode([
        "status" => "success",
        "message" => "Đã đăng xuất"
    ]);
    exit;
}


    public function checkSession()
    {
        if (isset($_SESSION['user'])) {
            echo json_encode([
                "status" => "success",
                "user" => $_SESSION['user']
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Chưa đăng nhập"
            ]);
        }
        exit;
    }
}
