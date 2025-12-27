<?php
/*require_once("./core/database.php");

class ReportModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function totalRevenue()
    {
        $sql = "SELECT SUM(total) FROM orders WHERE status = 'completed'";
        return $this->conn->query($sql)->fetchColumn() ?? 0;
    }

    public function totalOrders()
    {
        return $this->conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    }

    public function totalUsers()
    {
        return $this->conn->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    public function revenueByDay()
    {
        $sql = "
            SELECT DATE(created_at) AS day, SUM(total) AS revenue
            FROM orders
            WHERE status='completed'
            GROUP BY DATE(created_at)
            ORDER BY day
        ";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function topProducts()
    {
        $sql = "
            SELECT p.name AS product_name, SUM(oi.quantity) AS total_sold
            FROM order_items oi
            JOIN product_variants pv ON oi.product_variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            GROUP BY p.id
            ORDER BY total_sold DESC
            LIMIT 5
        ";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function orderStatus()
    {
        $sql = "
            SELECT status, COUNT(*) AS count
            FROM orders
            GROUP BY status
        ";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    
}
*/

require_once "./core/database.php";

class ReportModel
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->connect(); // 👈 FIX lỗi Undefined property $con
    }

    // ===== OVERVIEW =====
    public function totalRevenue()
    {
        $sql = "SELECT SUM(total) FROM orders WHERE status = 'completed'";
        return $this->con->query($sql)->fetchColumn() ?? 0;
    }

    public function totalOrders()
    {
        $sql = "SELECT COUNT(*) FROM orders";
        return $this->con->query($sql)->fetchColumn();
    }

    public function totalUsers()
    {
        $sql = "SELECT COUNT(*) FROM users";
        return $this->con->query($sql)->fetchColumn();
    }

    // ===== DOANH THU THEO NGÀY =====
    public function revenueByDay()
    {
        $sql = "
            SELECT 
                DATE(created_at) AS day,
                SUM(total) AS revenue
            FROM orders
            WHERE status = 'completed'
            GROUP BY DATE(created_at)
            ORDER BY day ASC
        ";

        return $this->con->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===== TOP SẢN PHẨM =====
    public function topProducts()
    {
        $sql = "
            SELECT 
                p.name AS product_name,
                SUM(oi.quantity) AS total_sold
            FROM order_items oi
            JOIN product_variants pv ON oi.product_variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            GROUP BY p.id
            ORDER BY total_sold DESC
            LIMIT 5
        ";

        return $this->con->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===== TRẠNG THÁI ĐƠN =====
    public function orderStatus()
    {
        $sql = "
            SELECT 
                status,
                COUNT(*) AS count
            FROM orders
            GROUP BY status
        ";

        return $this->con->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ===== ĐƠN THEO KHOẢNG NGÀY =====
    public function getOrdersByDate($from, $to)
{
    $sql = "
        SELECT 
            o.id,
            o.created_at,
            o.total,
            o.status,
            u.username,
            u.email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE (:from IS NULL OR DATE(o.created_at) >= :from)
          AND (:to IS NULL OR DATE(o.created_at) <= :to)
        ORDER BY o.created_at DESC
    ";

    $stmt = $this->con->prepare($sql);
    $stmt->execute([
        ":from" => $from,
        ":to" => $to
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function compareMonthRevenue()
{
    $sql = "
        SELECT
            SUM(
                CASE
                    WHEN MONTH(created_at) = MONTH(CURDATE())
                     AND YEAR(created_at) = YEAR(CURDATE())
                    THEN total
                    ELSE 0
                END
            ) AS current_month,

            SUM(
                CASE
                    WHEN MONTH(created_at) = MONTH(CURDATE() - INTERVAL 1 MONTH)
                     AND YEAR(created_at) = YEAR(CURDATE() - INTERVAL 1 MONTH)
                    THEN total
                    ELSE 0
                END
            ) AS last_month
        FROM orders
        WHERE status = 'completed'
    ";

    $stmt = $this->con->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

}
