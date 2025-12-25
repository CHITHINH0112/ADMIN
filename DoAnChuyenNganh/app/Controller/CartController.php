<?php
require_once("./app/Service/CartService.php");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}



class CartController
{
    private $cartService;

    public function __construct()
    {
        $this->cartService = new CartService();
    }

    // Xem giỏ hàng
    public function getCartByUser()
    {

        $user_id = $_SESSION['user']['id'] ?? null;
        session_write_close();
        if (!$user_id) {
            echo json_encode(["error" => "Chưa đăng nhập"]);
            return;
        }

        echo json_encode($this->cartService->getCart($user_id));
    }

    // Thêm variant vào giỏ
    public function addToCart()
    {

        $user_id = $_SESSION['user']['id'] ?? null;
        session_write_close();
        $body = json_decode(file_get_contents("php://input"), true);
        $product_variant_id = $body['product_variant_id'] ?? null;
        $quantity = $body['quantity'] ?? 1;

        if (!$product_variant_id) {
            echo json_encode(["error" => "Thiếu product_variant_id"]);
            return;
        }

        echo json_encode($this->cartService->addToCart($user_id, $product_variant_id, $quantity));
    }

    // Cập nhật số lượng item trong cart
    public function updateItem()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $item_id = $body['item_id'] ?? null;
        $quantity = $body['quantity'] ?? 1;

        if (!$item_id) {
            echo json_encode(["error" => "Thiếu item_id"]);
            return;
        }

        $success = $this->cartService->updateItem($item_id, $quantity);
        echo json_encode(["success" => $success]);
    }

    // Xóa 1 item trong cart
    public function removeItem()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $item_id = $body['item_id'] ?? null;

        if (!$item_id) {
            echo json_encode(["error" => "Thiếu item_id"]);
            return;
        }

        $success = $this->cartService->removeItem($item_id);
        echo json_encode(["success" => $success]);
    }

    // Xóa toàn bộ cart
    public function clearCart()
    {

        $user_id = $_SESSION['user']['id'] ?? null;
        session_write_close();

        if (!$user_id) {
            echo json_encode(["error" => "Chưa đăng nhập"]);
            return;
        }

        $success = $this->cartService->clearCart($user_id);
        echo json_encode(["success" => $success]);
    }
    // Đồng bộ giỏ hàng từ frontend
    public function syncCart()
    {

        $user_id = $_SESSION['user']['id'] ?? null;
        session_write_close();

        if (!$user_id) {
            echo json_encode(["error" => "Chưa đăng nhập"]);
            return;
        }

        $body = json_decode(file_get_contents("php://input"), true);
        $items = $body["items"] ?? [];

        echo json_encode($this->cartService->syncCart($user_id, $items));
    }
}
