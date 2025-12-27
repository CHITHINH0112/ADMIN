<?php
/*require_once("./app/Model/ReportModel.php");
require_once("./app/Middleware/AdminMiddleware.php");

class ReportService
{
    private $model;

    public function __construct()
    {
        $this->model = new ReportModel();
    }

    public function overview()
    {
        AdminMiddleware::requireAdmin();

        return [
            "totalRevenue" => $this->model->totalRevenue(),
            "totalOrders" => $this->model->totalOrders(),
            "totalUsers" => $this->model->totalUsers()
        ];
    }

    public function revenueByDay()
    {
        AdminMiddleware::requireAdmin();
        return $this->model->revenueByDay();
    }

    public function topProducts()
    {
        AdminMiddleware::requireAdmin();
        return $this->model->topProducts();
    }

    public function orderStatus()
    {
        AdminMiddleware::requireAdmin();
        return $this->model->orderStatus();
    }

    public function ordersByDate($from, $to)
{
    return $this->model->getOrdersByDate($from, $to);
}

}
*///

require_once "./app/Model/ReportModel.php";

class ReportService
{
    private $model;

    public function __construct()
    {
        $this->model = new ReportModel();
        
    }

    // OVERVIEW
    public function overview()
    {
        return [
            "totalRevenue" => $this->model->totalRevenue(),
            "totalOrders"  => $this->model->totalOrders(),
            "totalUsers"   => $this->model->totalUsers()
        ];
    }

    // DOANH THU THEO NGÀY
    public function revenueByDay()
    {
        return [
            "data" => $this->model->revenueByDay()
        ];
    }

    // TOP SẢN PHẨM
    public function topProducts()
    {
        return [
            "data" => $this->model->topProducts()
        ];
    }

    // TRẠNG THÁI ĐƠN
    public function ordersByDate($from, $to)
{
    return [
        "data" => $this->model->getOrdersByDate($from, $to)
    ];
}

    // GET report/orders?from=YYYY-MM-DD&to=YYYY-MM-DD
public function orders()
{
    $from = $_GET['from'] ?? null;
    $to   = $_GET['to'] ?? null;

    echo json_encode([
        "data" => $this->service->ordersByDate($from, $to)
    ]);
}
public function compareMonth()
{
    return [
        "data" => $this->model->compareMonthRevenue()
    ];
}


}
