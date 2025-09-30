<?php
require_once __DIR__ . '/../config/db.php';
Database::initTables();
require __DIR__ . '/../vendor/autoload.php';

use Src\Controllers\PagesController;
use Src\Controllers\RealEstateController;

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Remove "century-group/public" from URI
$uri = str_replace('century-group/public', '', $uri);
$uri = trim($uri, '/');

// Simple router
switch ($uri) {
    case '':
    case 'home':
        (new PagesController())->home();
        break;

    case 'about':
        (new PagesController())->about();
        break;

    case 'contact':
        (new PagesController())->contact();
        break;

    case 'careers':
        (new PagesController())->careers();
        break;

    case 'industries':
        (new PagesController())->industries();
        break;

    case 'realestate':
        (new RealEstateController())->index();
        break;

    case 'projects':
        (new PagesController())->projects();
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/../views/404.php';
        break;
}
