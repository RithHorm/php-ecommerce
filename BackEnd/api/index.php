<?php
// Include necessary files
require_once __DIR__ . '/Router.php'; 
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../route/authRoute.php';
require_once __DIR__ . '/../route/userRoute.php';
require_once __DIR__ . '/../route/productRoute.php';

// Create an instance of the Router class
$router = new Router();

// Register routes using functions from route files, passing the $router object
authRoutes($router);
userRoutes($router);
productRoutes($router);

// Get URI and method from the request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/');  // Remove trailing slash if any

// Remove base path (/php-rest-api/api) from the URI
$requestUri = str_replace('/php-rest-api/BackEnd/api', '', $requestUri);

// Get HTTP method (GET, POST, etc.)
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Dispatch the request to the appropriate route
$router->dispatch($requestUri, $requestMethod);
?>
