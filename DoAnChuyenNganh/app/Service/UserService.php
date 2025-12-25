<?php
require_once "./app/Model/UserModel.php";
require_once("./app/Middleware/AdminMiddleware.php");
require_once("./app/Middleware/AuthMiddleware.php");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

class UserService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getAll()
    {
        AdminMiddleware::requireAdmin();
        return $this->userModel->getAll();
    }

    public function getById($id)
    {
        AdminMiddleware::requireAdmin();
        return $this->userModel->getById($id);
    }

    public function getByUserName($username)
    {
        $user =  $this->userModel->findByUsername($username);
        if ($user)
            return ["status" => "success", "user" => $user];
        else
            return ["status" => "error"];
    }


    public function update($id, $data)
    {

        AuthMiddleware::requireLogin();
        return $this->userModel->update(
            $id,
            $data["username"],
            $data["email"],
            $data["phone"],
            $data["role"]
        );
    }

    public function delete($id)
    {
        AdminMiddleware::requireAdmin();
        return $this->userModel->delete($id);
    }

    public function changeStatus($id, $status)
{
    AdminMiddleware::requireAdmin();
    return $this->userModel->updateStatus($id, $status);
}



public function getUsersWithLastOrder()
{
    AdminMiddleware::requireAdmin();
    $users = $this->userModel->getAll();

    foreach ($users as &$u) {
        $u["last_order_at"] = $this->userModel->getLastOrderTime($u["id"]);
    }

    return $users;
}

public function resetPassword($id)
{
    AdminMiddleware::requireAdmin();
    $newPass = password_hash("123456", PASSWORD_BCRYPT);
    return $this->userModel->resetPassword($id, $newPass);
}



}
