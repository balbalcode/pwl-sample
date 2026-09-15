<?php

// When running via `php -S host:port index.php`, the built-in server routes
// every request through this file. Let it serve real static files (CSS, JS,
// images) directly instead of running them through the app. Apache doesn't
// need this — .htaccess already leaves real files alone.
if (PHP_SAPI === 'cli-server') {
    $requestedFile = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($requestedFile)) {
        return false;
    }
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';

// Parse URL into segments
// e.g. /pwl/products/550e8400-e29b-41d4-a716-446655440000/edit
//      → ['products', '550e8400-e29b-41d4-a716-446655440000', 'edit']
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base     = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path     = trim(substr($uri, strlen($base) + 1), '/');
$segments = explode('/', $path);

define('BASE_URL', $base === '' ? '' : '/' . $base);

$uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

$page   = $segments[0] ?: 'products';
$id     = isset($segments[1]) && preg_match($uuidPattern, $segments[1]) ? $segments[1] : null;
$action = $id !== null ? ($segments[2] ?? 'show') : ($segments[1] ?? 'index');

$method = $_SERVER['REQUEST_METHOD']; // GET or POST

$controller = __DIR__ . '/controllers/' . ucfirst($page) . '.php';

if (file_exists($controller)) {
    require_once $controller;
} else {
    http_response_code(404);
    echo '404 - Page not found';
}
