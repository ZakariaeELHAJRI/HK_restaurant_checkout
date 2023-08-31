<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Credentials: true');
$method = $_SERVER['REQUEST_METHOD'];
// Get the request data
$data = json_decode(file_get_contents("php://input"), true);
// Routing
$uri = $_SERVER['REQUEST_URI'];
$base_path = '/api_orders/api/index.php/'; // don't forget to change /api_orders/api/ to your own path
$endpoint = str_replace($base_path, '', $uri);

// Split the endpoint into parts
$parts = explode('/', $endpoint);
$resource = array_shift($parts);
$controller = null;
if ($resource === 'login') {
    require_once('./controllers/LoginController.php');
    $loginController = new LoginController();
    
    if ($method === 'POST') {
        $response = $loginController->authenticateUser($data['email'], $data['password']);
        echo json_encode($response);
        exit;
    }
}
if ($resource === 'commandall') {
    require_once('./controllers/CommandController.php');
        $controller = new CommandController();
    
    if ($method === 'GET') {
        $response = $controller->getCommandAndLigneCommand();
        echo json_encode($response);
        exit;
    }
}
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
    case 'commandall' :
        require_once('./controllers/CommandController.php');
        $controller = new CommandController();
    
    if ($method === 'GET') {
        $response = $controller->getCommandAndLigneCommand();
        echo json_encode($response);
        exit;
    }
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
