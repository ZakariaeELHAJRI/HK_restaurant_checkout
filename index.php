<?php
header("Content-Type: application/json");

// Get the HTTP method (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Get the request data
$data = json_decode(file_get_contents("php://input"), true);

// Routing
$uri = $_SERVER['REQUEST_URI'];
$base_path = '/api_orders/api/index.php/';
$endpoint = str_replace($base_path, '', $uri);

// Split the endpoint into parts
$parts = explode('/', $endpoint);
// Extract the first part as the resource name (student or command)
$resource = array_shift($parts);
// Based on the resource name, instantiate the appropriate controller
$controller = null;
switch ($resource) {
    case 'user':
        require_once('./controllers/UserController.php');
        $controller = new UserController();
        break;
    case 'product':
        require_once('./controllers/ProductController.php');
        $controller = new ProductController();
        break;
    case 'command':
        require_once('./controllers/CommandController.php');
        $controller = new CommandController();
        break;
    case 'ligneCommand':
        require_once('./controllers/LigneCommandController.php');
        $controller = new LigneCommandController();
        break;
    case 'category':
        require_once('./controllers/CategoryController.php');
        $controller = new CategoryController();
        break;
    case 'bill':
        require_once('./controllers/BillController.php');
        $controller = new BillController();
        break;
    
    default:
        echo $resource;
        echo json_encode(['success' => false, 'message' => 'Invalid API endpoint']);
        exit;
}

// Handle the request and get the response
$response = $controller->handleRequest($method, $data);

// Send the response as JSON
echo json_encode($response);
