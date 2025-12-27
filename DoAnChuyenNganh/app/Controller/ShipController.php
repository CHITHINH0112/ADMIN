<?php
require_once("./app/Service/ShipProviderService.php");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


class ShipProviderController
{
    private $shipService;

    public function __construct()
    {

        $this->shipService = new ShipProviderService();
    }

    public function index()
    {
        echo json_encode($this->shipService->getAll());
    }

    public function getById($id)
    {
        echo json_encode($this->shipService->getById($id));
    }

    public function insert()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->shipService->insert($body));
    }

    public function update()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->shipService->update($body));
    }

    public function delete($id)
    {
        echo json_encode($this->shipService->delete($id));
    }
    public function getprice($id)
    {
        echo json_encode($this->shipService->getprice($id));
    }

    public function updatePrice()
{
    $body = json_decode(file_get_contents("php://input"), true);
    echo json_encode($this->shipService->updatePrice($body));
}

}
