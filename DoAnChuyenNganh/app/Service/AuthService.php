<?php



require_once "./core/Session.php";
require_once "./app/Model/UserModel.php";
require_once("./app/Middleware/ValidationMiddleware.php");

header("Content-Type: application/json");
header("Access-Control-Allow-Credentials: true");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


class AuthService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }


    // Đăng ký
    public function register($username, $password, $repassword, $email, $phone)
    {
        // 1. CHECK VALIDATION

        if ($this->userModel->findByUsername($username)) {
            return ["status" => "error", "message" => "Tên người dùng đã tồn tại!"];
        }
        if ($password !== $repassword) {
            return ["status" => "error", "message" => "Mật khẩu không trùng khớp!"];
        }
        if (!isEmail($email)) {
            return ["status" => "error", "message" => "Email không hợp lệ!"];
        }
        if (!isValidPhone($phone)) {
            return ["status" => "error", "message" => "Số điện thoại không hợp lệ!"];
        }

        // 2. TẠO USER
        $hash = hashPassword($password, PASSWORD_BCRYPT);
        $ok = $this->userModel->create($username, $hash, $email, $phone);

        // 3. XỬ LÝ KẾT QUẢ
        if ($ok) {
            // Lấy thông tin user vừa tạo
            $user = $this->userModel->findByUsername($username);

            // QUAN TRỌNG: Xóa password hash đi trước khi trả về Frontend (Bảo mật)
            if (isset($user['password'])) {
                unset($user['password']);
            }
            Session::set("user", [
                "id" => $user["id"],
                "username" => $user["username"],
                "role" => $user["role"],
                "email" => $user["email"]
            ]);

            // Trả về status success và cục user
            return [
                "status" => "success",
                "message" => "Đăng ký thành công!",
                "user" => $user
            ];
        } else {
            return ["status" => "error", "message" => "Lỗi hệ thống, không thể tạo tài khoản!"];
        }
    }

    // Đăng nhập
    public function login($username, $password)
    {
        $user = $this->userModel->findByUsername($username);

        if (!$user) {
            echo json_encode(["status" => "error", "message" => "Không tìm thấy tên đăng nhập!"]);
            exit();
        }

        if (!verifyPassword($password, $user["password"])) {
            echo json_encode(["status" => "error", "message" => "Sai mật khẩu!"]);
            exit();
        }


        // Khởi tạo session và lưu user
        Session::set("user", [
            "id" => $user["id"],
            "username" => $user["username"],
            "role" => $user["role"],
            "email" => $user["email"]
        ]);


        echo json_encode([
            "status" => "success",
            "user" => [
                "id" => $user['id'],
                "username" => $user['username'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ]);
        exit();
    }


    // Đăng xuất
    public function logout()
    {
        Session::destroy();
        return true;
    }
}