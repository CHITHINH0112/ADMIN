<?php
require_once("./app/Service/OrderService.php");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
require_once("./app/Service/CartService.php");
require_once("./app/Service/ShipProviderService.php");




if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


class OrderController
{
    private $orderService;
    private $cartService;
    private $shipService;

    public function __construct()
    {


        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->shipService = new ShipProviderService();
    }

    /*** ADMIN METHODS ***/

    // Lấy tất cả đơn hàng
    public function index()
    {
        echo json_encode($this->orderService->getAllOrders());
    }


    // Lấy đơn hàng của 1 user
    public function getOrdersByUser($user_id)
    {
        echo json_encode($this->orderService->getOrdersByUser($user_id));
    }

    // Thêm sản phẩm variant vào đơn hàng (admin)
    public function addItem()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->orderService->addItem(
            $body["order_id"],
            $body["product_variant_id"], // dùng variant
            $body["quantity"],
            $body["price"]
        ));
    }

    // Xóa 1 item khỏi đơn hàng
    public function deleteItem($item_id)
    {
        echo json_encode($this->orderService->deleteItem($item_id));
    }

    // Xóa tất cả item trong đơn
    public function clearItems($order_id)
    {
        echo json_encode($this->orderService->clearOrderItems($order_id));
    }

    // Cập nhật trạng thái đơn
    public function updateStatus()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->orderService->updateStatus(
            $body["order_id"],
            $body["status"]
        ));
    }

    // Cập nhật trạng thái giao hàng
    public function updateDelivery()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->orderService->updateDeliveryStatus(
            $body["order_id"],
            $body["delivery_status"]
        ));
    }

    // Cập nhật đơn vị vận chuyển
    public function updateShipping()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->orderService->updateShipping(
            $body["order_id"],
            $body["shipping_id"]
        ));
    }

    // Cập nhật tổng tiền đơn
    public function updateTotal()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->orderService->updateTotal(
            $body["order_id"],
            $body["total"]
        ));
    }
    // user thêm địa chỉ nhận hàng mới
    public function address()
    {

        $user_id = $_SESSION['user']['id'] ?? null;
        session_write_close();

        $body = json_decode(file_get_contents("php://input"), true);

        echo json_encode($this->orderService->addUserAddress(
            $user_id,
            $body['address'] ?? '',
            $body['phone'] ?? '',
            $body['is_default'] ?? 0
        ));
    }
    // Xóa toàn bộ đơn hàng
    public function delete($order_id)
    {
        echo json_encode($this->orderService->deleteItem($order_id));
    }

    public function deleteAddressUser()
    {
        $user_id = $_SESSION['user']['id'] ?? null;
        session_write_close();

        // 1. KIỂM TRA ĐĂNG NHẬP
        if (!$user_id) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Vui lòng đăng nhập"]);
            return;
        }
        $body = json_decode(file_get_contents("php://input"), true);
        $address_id = $body['address_id'] ?? null;


        if (!$address_id) {
            http_response_code(400); // Bad Request
            echo json_encode(["status" => "error", "message" => "Thiếu ID địa chỉ"]);
            return;
        }
        // 3. GỌI SERVICE (Service sẽ chứa logic bảo mật)
        $res = $this->orderService->deleteAddressUser($address_id, $user_id);

        // 4. XỬ LÝ KẾT QUẢ VÀ TRẢ VỀ STATUS CODE
        if ($res['status'] === 'success') {
            http_response_code(200);
            echo json_encode($res);
        } elseif ($res['status'] === 'not_found') {
            http_response_code(404); // Không tìm thấy hoặc không thuộc sở hữu
            echo json_encode($res);
        } else {
            http_response_code(500); // Lỗi server
            echo json_encode($res);
        }
    }


    public function checkout()
    {

        $user_id = $_SESSION['user']['id'] ?? null;
        Session::writeClose();
        if (!$user_id) {
            echo json_encode(["status" => "error", "message" => "Chưa đăng nhập"]);
            return;
        }

        $items = $this->orderService->getCartCheckout($user_id);
        echo json_encode($items);
    }

    public function confirm()
    {
        $user_id = $_SESSION['user']['id'] ?? null;
        Session::writeClose();

        if (!$user_id) {
            http_response_code(401);
            echo json_encode(["status" => "error", "message" => "Chưa đăng nhập"]);
            return;
        }

        $body = json_decode(file_get_contents("php://input"), true);
        $shipping_id = $body['shipping_id'] ?? null;
        $payment_method_id = $body['payment_method'] ?? null;
        $address_id = $body['address_id'] ?? null;

        if ($user_id && $address_id) {
            $this->orderService->setDefault($address_id, $user_id);
        }
        $cartData = $this->orderService->getCartCheckout($user_id);
        // Lấy thông tin địa chỉ đã chọn
        $addressInfo = $this->orderService->getAddressById($address_id);
        $shipname = $this->orderService->getShipName($shipping_id);
        // Lấy tên phương thức thanh toán
        $paymentMethodName = $this->orderService->getPaymentMethodName($payment_method_id);
        $subTotal = $cartData['total'] ?? 0;
        $shippingFee = $this->shipService->getprice($shipping_id);
        $finalTotal = $subTotal + $shippingFee["price"];
        $orderRes = $this->orderService->createOrder(
            $user_id,
            $finalTotal, // Tổng tiền cuối cùng
            $shipping_id,
            $shipname["name"],
            $addressInfo['phone'] ?? '',
            $addressInfo['address'] ?? "toilaai1133",
            $paymentMethodName,
            $shippingFee["price"]

        );

        if ($orderRes['status'] !== 'success') {
            echo json_encode(["status" => "error", "message" => $orderRes['message']]);
            return;
        }
        $order_id = $orderRes['order_id'];
        // --- 3. INSERT ORDER ITEMS & KIỂM TRA ---
        $all_ok = true;
        foreach ($cartData["items"] as $item) {
            $ok = $this->orderService->createOrderItem(
                $order_id,
                $item["product_variant_id"],
                $item["quantity"],
                $item["price"] // Giá của từng item
            );
            if (!$ok) {
                $all_ok = false;
                break;
            }
        }
        // KIỂM TRA SAU KHI INSERT (KHÔNG CÓ ROLLBACK Ở ĐÂY)
        if (!$all_ok) {
            // Lỗi này cần LOG và xử lý tay, vì không có Transaction
            echo json_encode(["status" => "error", "message" => "Lỗi thêm sản phẩm vào đơn hàng. Đơn hàng đã được tạo."]);
            return;
        }
        // --- 4. HOÀN THÀNH ---
        // Xóa giỏ hàng (Giả định CartService có hàm clearCart($user_id))
        $this->cartService->clearCart($user_id);
        echo json_encode([
            "status" => "success",
            "order_id" => $order_id,
            "message" => "Đặt hàng thành công"
        ]);
        return;
    }
    public function getOrder($order_id)
    {
        // Bỏ qua xác thực user_id ở đây nếu bạn muốn Admin cũng dùng được.
        // Nếu muốn bảo mật, phải xác thực user_id === order['user_id']
        $res = $this->orderService->getOrder($order_id);
        echo json_encode($res);
    }
}
