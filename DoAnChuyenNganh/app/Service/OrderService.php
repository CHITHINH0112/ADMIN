<?php
require_once("./app/Model/OrderModel.php");
require_once("./app/Model/Order_ItemModel.php");
require_once("./app/Model/CartModel.php");
require_once("./app/Model/Cart_ItemModel.php");
require_once("./app/Model/PaymentModel.php");
require_once("./app/Model/ShipModel.php");
require_once("./app/Model/UserAdressModel.php");
require_once("./app/Model/ProductModel.php");

// Headers config
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


class OrderService
{
    private $orderModel;
    private $orderItemModel;
    private $cartModel;
    private $cartItemModel;
    private $shipModel;
    private $paymentModel;
    private $productModel;
    private $userAddressModel;
    private $userModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->shipModel = new ShipProviderModel();
        $this->paymentModel = new PaymentModel();
        $this->productModel = new Product();
        $this->userAddressModel = new UserAddressModel();
        $this->userModel = new UserModel();
    }

    // Lấy tất cả đơn hàng
    public function getAllOrders()
    {
        return $this->orderModel->getAll();
    }
    public function getInforUser($id)
    {
        return $this->userModel->getById($id);
    }

    // Lấy chi tiết đơn hàng
    public function getOrder($order_id)
    {
        $order = $this->orderModel->getById($order_id);
        if (!$order) return ["error" => "Order not found"];
        $items = $this->orderItemModel->getByOrderId($order_id);
        return ["order" => $order, "items" => $items];
    }

    public function getShipName($id)
    {
        return $this->shipModel->getById($id);
    }

    public function getOrdersByUser($user_id)
    {
        // Lấy danh sách đơn hàng
        $orders = $this->orderModel->getByUser($user_id);

        if (empty($orders)) return [];

        // Duyệt qua từng đơn hàng
        foreach ($orders as &$order) {
            // A. Lấy chi tiết sản phẩm
            $orderItems = $this->orderItemModel->getItemsByOrderId($order['id']);

            // Bổ sung thông tin tên/ảnh cho items
            foreach ($orderItems as &$item) {
                $variant = $this->productModel->getVariantById($item['product_variant_id']);
                $product = $this->productModel->getById($variant['product_id']);

                $item['product_name'] = $product['name'];
                $item['image'] = $variant['image'] ?? $variant['variant_image'] ?? $product['image'];
                $item['color_id'] = $variant['color_id'];
                $item['size_id'] = $variant['size_id'];
            }
            $order['items'] = $orderItems;

            // B. [TỐI ƯU HÓA] Hiển thị thông tin nhận hàng từ Snapshot
            $order['address_text'] = $order['shipping_address'] ?? 'N/A';
            $order['phone'] = $order['shipping_phone'] ?? 'N/A';
            $order['receiver_name'] = $order['shipping_name'] ?? 'N/A';
            // Hiển thị phương thức thanh toán
            $order['payment_method_text'] = $order['payment_method'] ?? 'COD';
        }

        return $orders;
    }
    public function deleteAddressUser($address_id, $user_id)
    {
        $success =  $this->userAddressModel->delete($address_id, $user_id);
        if ($success) {

            return ["status" => "success", "message" => "Xóa địa chỉ thành công."];
        } else {
            return ["status" => "error", "message" => "Lỗi hệ thống khi xóa địa chỉ."];
        }
    }

    // Thêm địa chỉ mới cho user
    public function addUserAddress($user_id, $address, $phone, $is_default = 0)
    {
        $this->userAddressModel->unsetDefault($user_id);
        $success = $this->userAddressModel->insert($user_id, $address, $phone, $is_default);
        if ($success) {
            $lastid = $this->userAddressModel->getLastInsertId();
            return ["message" => "Address added", "address_id" => $lastid];
        } else
            return ["error" => "Failed to add address"];
    }


    public function addItem($order_id, $product_variant_id, $quantity, $price)
    {
        $ok = $this->orderItemModel->insert($order_id, $product_variant_id, $quantity, $price);
        return $ok ? ["message" => "Item added successfully"] : ["error" => "Failed to add item"];
    }

    // Các hàm Update giữ nguyên
    public function updateStatus($id, $status)
{
    AdminMiddleware::requireAdmin();
    return $this->orderModel->updateStatus($id, $status);
}

public function updateDeliveryStatus($id, $delivery_status)
{
    AdminMiddleware::requireAdmin();
    return $this->orderModel->updateDeliveryStatus($id, $delivery_status);
}


    public function updateShipping($order_id, $shipping_id)
    {
        $this->orderModel->updateShipping($order_id, $shipping_id);
        return ["message" => "Shipping updated"];
    }

    public function updateTotal($order_id, $total)
    {
        $this->orderModel->updateTotal($order_id, $total);
        return ["message" => "Total updated"];
    }

    public function deleteItem($item_id)
    {
        $this->orderItemModel->deleteItem($item_id);
        return ["message" => "Item deleted"];
    }

    public function clearOrderItems($order_id)
    {
        $this->orderItemModel->deleteByOrder($order_id);
        return ["message" => "All items removed"];
    }

    public function getCartCheckout($user_id)
    {
        $cart = $this->cartModel->getCartByUser($user_id);
        if (!$cart) return ["error" => "Cart not found"];

        $items = $this->cartItemModel->getItemsByCart($cart['id']);
        $addresses = $this->userAddressModel->getByUser($user_id);

        $total = 0;

        foreach ($items as &$item) {
            $variant = $this->productModel->getVariantById($item['product_variant_id']);
            $product = $this->productModel->getById($variant['product_id']);

            $item['price'] = $variant['price'];
            $item['product_name'] = $product['name'];
            $item['image'] = $product['image'] ?? '';
            $item['color_id'] = $variant['color_id'];
            $item['size_id'] = $variant['size_id'];

            $total += $item['quantity'] * $variant['price'];
        }

        return [
            "cart" => $cart,
            "items" => $items,
            "total" => $total,
            "shippingOptions" => $this->shipModel->getAll(),
            "paymentOptions" => $this->paymentModel->getAll(),
            "addresses" => $addresses
        ];
    }
    public function setDefault($address_id, $user_id)
    {
        $this->userAddressModel->unsetDefault($user_id);
        return $this->userAddressModel->setDefault($address_id, $user_id);
    }

    public function getUserAdress($user_id)
    {
        return $this->userAddressModel->getDefault($user_id);
    }
    // Thay thế trong OrderService.php

    // 1. Hàm tạo Order (Sửa lại để nhận đúng tham số cần thiết)
    public function createOrder(
        $user_id,
        $total,
        $shipping_id,
        $shipping_name,
        $shipping_phone,
        $shipping_address,
        $payment_method,
        $shipping_fee
    ) {
        // Tham số còn thiếu: $status, $delivery_status (sẽ dùng default)
        $order_id = $this->orderModel->insert(
            $user_id,
            $total,
            "pending", // status
            $shipping_id,
            "pending", // delivery_status
            $shipping_name,
            $shipping_phone,
            $shipping_address,
            $payment_method,
            $shipping_fee
        );

        if (!$order_id) return ["status" => "error", "message" => "Failed to create order"];
        return ["status" => "success", "order_id" => $order_id];
    }

    // 2. Hàm tạo Order Item (Insert đơn giản)
    public function createOrderItem(
        $order_id,
        $product_variant_id,
        $quantity,
        $price
    ) {
        // Trả về TRUE/FALSE để Controller dễ kiểm tra
        return $this->orderItemModel->insert($order_id, $product_variant_id, $quantity, $price);
    }

    // 3. Hàm lấy Thông tin Địa chỉ (Lấy theo ID)
    public function getAddressById($id)
    {
        $addressInfo = $this->userAddressModel->getById($id);
        if (!$addressInfo) return ["status" => "error", "message" => "Address not found"];
        return $addressInfo;
    }

    // 4. Hàm lấy Tên Phương thức Thanh toán (Lấy theo ID)
    public function getPaymentMethodName($payment_method_id)
    {
        $paymentInfo = $this->paymentModel->getById($payment_method_id);
        if (!$paymentInfo) return 'COD'; // Mặc định nếu không tìm thấy
        return $paymentInfo['method'] ?? $paymentInfo['name'] ?? 'COD';
    }

public function getAll()
{
    AdminMiddleware::requireAdmin();
    return $this->orderModel->getAllWithUser();
}

public function getDetail($id)
{
    AdminMiddleware::requireAdmin();
    return $this->orderModel->getOrderDetail($id);
}

public function detail($id)
{
    AdminMiddleware::requireAdmin();
    return $this->orderModel->getDetail($id);
}

}
