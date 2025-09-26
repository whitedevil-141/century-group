<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/controllers/UserController.php';

// Basic router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        echo "Welcome to My Project!";
        break;
    case '/register':
        (new UserController($pdo))->register();
        break;
    case '/login':
        (new UserController($pdo))->login();
        break;
    case '/logout':
        (new UserController($pdo))->logout();
        break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
