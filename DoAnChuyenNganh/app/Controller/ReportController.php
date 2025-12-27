<?php
/*require_once("./app/Service/ReportService.php");

class ReportController
{
    private $service;

    public function __construct()
    {
        $this->service = new ReportService();
    }

    public function overview()
    {
        echo json_encode($this->service->overview());
    }

    public function revenueByDay()
    {
        echo json_encode($this->service->revenueByDay());
    }

    public function topProducts()
    {
        echo json_encode($this->service->topProducts());
    }

    public function orderStatus()
    {
        echo json_encode($this->service->orderStatus());
    }
    // GET report/orders
public function orders()
{
    $from = $_GET['from'] ?? null;
    $to   = $_GET['to'] ?? null;

    echo json_encode([
        "status" => "success",
        "data" => $this->service->ordersByDate($from, $to)
    ]);
}

}
*/

require_once "./app/Service/ReportService.php";

class ReportController
{
    private $service;

    public function __construct()
    {
        $this->service = new ReportService();
    }

    public function overview()
    {
        echo json_encode($this->service->overview());
    }
public function revenueByDay()
{
    echo json_encode([
        "data" => $this->service->revenueByDay()['data']
    ]);
}

public function topProducts()
{
    echo json_encode([
        "data" => $this->service->topProducts()['data']
    ]);
}

public function orderStatus()
{
    echo json_encode([
        "data" => $this->service->orderStatus()['data']
    ]);
}

// GET report/orders?from=YYYY-MM-DD&to=YYYY-MM-DD
public function orders()
{
    $from = $_GET['from'] ?? null;
    $to   = $_GET['to'] ?? null;

    $result = $this->service->ordersByDate($from, $to);

    echo json_encode([
        "data" => $result['data']
    ]);
}

// GET report/compareMonth
public function compareMonth()
{
    echo json_encode(
        $this->service->compareMonth()
    );
}


}
