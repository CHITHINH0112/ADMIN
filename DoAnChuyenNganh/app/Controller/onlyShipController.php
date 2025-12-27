<?php
/*require_once("./app/Service/OnlyShipService.php");

class OnlyShipController
{
    private $service;

    public function __construct()
    {
        $this->service = new OnlyShipService();
    }

    public function index()
    {
        echo json_encode($this->service->getAll());
    }

    public function updatePrice()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->service->updatePrice($body));
    }
}
*/

require_once("./app/Service/OnlyShipService.php");

class OnlyShipController
{
    private $service;

    public function __construct()
    {
        $this->service = new OnlyShipService();
    }

    public function index()
    {
        echo json_encode($this->service->getAll());
    }

    public function insert()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->service->insert($body));
    }

    public function update()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->service->update($body));
    }

    public function updatePrice()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->service->updatePrice($body));
    }

    public function delete($id)
    {
        echo json_encode($this->service->delete($id));
    }
}
