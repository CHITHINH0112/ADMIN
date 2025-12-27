<?php
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allowedOrigins = ['http://localhost:5173'];

if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
require_once "./core/Session.php";
Session::start();

require_once "app/Controller/ProductController.php";
require_once "app/Controller/CategoryController.php";
require_once "app/Controller/CartController.php";
require_once "app/Controller/OrderController.php";
require_once "app/Controller/PaymentController.php";
require_once "app/Controller/ShipController.php";
require_once "app/Controller/AuthController.php";
require_once "app/Controller/SubcategoryController.php";
require_once "app/Controller/UserController.php";
require_once "app/Controller/MetaController.php";
require_once "app/Controller/OnlyShipController.php";
require_once "app/Controller/ReportController.php";

if (isset($_GET['url'])) {
    $parts = explode('/', trim($_GET['url'], '/'));
    $controllerName = trim($parts[0] ?? 'product');
$action = trim($parts[1] ?? 'index');
$id = isset($parts[2]) ? trim($parts[2]) : null;

} else {
    $controllerName = $_GET['controller'] ?? 'product';
    $action = $_GET['action'] ?? 'index';
    $id = $_GET['id'] ?? null;
}

$controllers = [
    "product" => ProductController::class,
    "category" => CategoryController::class,
    "cart" => CartController::class,
    "order" => OrderController::class,
    "payment" => PaymentController::class,
    "ship" => ShipProviderController::class,
    "only-ship" => OnlyShipController::class,
    "auth" => AuthController::class,
    "subcategory" => SubcategoryController::class,
    "user" => UserController::class,
    "meta" => MetaController::class,
    "report" => ReportController::class

];

if (!isset($controllers[$controllerName])) {
    die(json_encode(["error" => "Controller not found"]));
}

$controller = new $controllers[$controllerName]();

// Check if method exists
if (!method_exists($controller, $action)) {
    die(json_encode(["error" => "Method '$action' not found in " . get_class($controller)]));
}

if ($id !== null) {
    $controller->$action($id);
} else {
    $controller->$action();
}



