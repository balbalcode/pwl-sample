<?php

require_once __DIR__ . '/config/database.php';

// Parse URL into segments
// e.g. /pwl/products/1/edit → ['products', '1', 'edit']
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base     = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path     = trim(substr($uri, strlen($base) + 1), '/');
$segments = explode('/', $path);

$page   = $segments[0] ?: 'products';
$id     = isset($segments[1]) && is_numeric($segments[1]) ? (int) $segments[1] : null;
$action = $segments[2] ?? ($segments[1] ?? 'index');

// If second segment is a word (not a number), treat it as action
if (!is_numeric($segments[1] ?? '') && isset($segments[1])) {
    $action = $segments[1];
}

$method = $_SERVER['REQUEST_METHOD']; // GET or POST

$controller = __DIR__ . "/controllers/{$page}Controller.php";

if (file_exists($controller)) {
    require_once $controller;
} else {
    http_response_code(404);
    echo '404 - Page not found';
}
