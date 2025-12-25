<?php
require_once("./core/database.php");

class OrderModel
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->connect();
    }

    // Lấy tất cả orders
    public function getAll()
    {
        $sql = "SELECT * FROM orders ORDER BY id DESC";
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy order theo id
    public function getById($id)
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy orders theo user_id
    public function getByUser($user_id)
    {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * [QUAN TRỌNG] Hàm Insert đã được sửa đổi theo chuẩn Thương mại điện tử:
     * 1. Sửa tên cột 'total_money' thành 'total' cho khớp với Database của bạn.
     * 2. Bỏ 'address_id', thay bằng 3 cột lưu trữ cứng: Name, Phone, Address.
     * 3. Thêm 'delivery_status'.
     */
    public function insert($user_id, $total, $status, $shipping_id, $delivery_status, $sh_name, $sh_phone, $sh_address, $payment_method, $shipping_fee)
    {
        // Thêm cột payment_method vào câu lệnh SQL
        $sql = "INSERT INTO orders 
                (user_id, total, status, shipping_id, delivery_status, shipping_name, shipping_phone, shipping_address, payment_method, created_at,shipping_fee) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(),?)";

        $stmt = $this->con->prepare($sql);

        try {
            $result = $stmt->execute([
                $user_id,
                $total,
                $status,
                $shipping_id,
                $delivery_status,
                $sh_name,
                $sh_phone,
                $sh_address,
                $payment_method, // <--- Truyền tham số mới vào đây
                $shipping_fee
            ]);
            return $this->con->lastInsertId();
        } catch (PDOException $e) {
            error_log("Lỗi SQL Insert Order: " . $e->getMessage());
            return false;
        }

        return false;
    }

    // Cập nhật trạng thái (status)
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    // Cập nhật shipping_id
    public function updateShipping($id, $shipping_id)
    {
        $sql = "UPDATE orders SET shipping_id = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([$shipping_id, $id]);
    }

    // Cập nhật delivery_status
    public function updateDeliveryStatus($id, $delivery_status)
    {
        $sql = "UPDATE orders SET delivery_status = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([$delivery_status, $id]);
    }

    // Cập nhật total
    public function updateTotal($id, $total)
    {
        $sql = "UPDATE orders SET total = ? WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([$total, $id]);
    }

    // Xóa order
    public function delete($id)
    {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([$id]);
    }

    // --- CÁC HÀM HỖ TRỢ TRANSACTION (BẮT BUỘC PHẢI CÓ) ---
    // Để bên Service gọi khi cần đảm bảo tính toàn vẹn dữ liệu

    public function beginTransaction()
    {
        return $this->con->beginTransaction();
    }

    public function commit()
    {
        return $this->con->commit();
    }

    public function rollBack()
    {
        return $this->con->rollBack();
    }
}
