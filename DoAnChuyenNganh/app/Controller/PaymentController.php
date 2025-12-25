<?php
require_once("./app/Service/PaymentService.php");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

class PaymentController
{
    private $paymentService;

    public function __construct()
    {

        $this->paymentService = new PaymentService();
    }

    public function getAllPayments()
    {
        echo json_encode($this->paymentService->getAllPayments());
    }

    public function getPaymentById($id)
    {
        echo json_encode($this->paymentService->getPaymentById($id));
    }

    public function getPaymentByOrderId($order_id)
    {
        echo json_encode($this->paymentService->getPaymentByOrderId($order_id));
    }

    public function addPayment($order_id, $method = 'cod', $status = 'pending', $paid_at = null)
    {
        echo json_encode($this->paymentService->addPayment($order_id, $method, $status, $paid_at));
    }

    public function updatePaymentStatus($id, $status, $paid_at = null)
    {
        echo json_encode($this->paymentService->updatePaymentStatus($id, $status, $paid_at));
    }

    public function deletePayment($id)
    {
        echo json_encode($this->paymentService->deletePayment($id));
    }
}
