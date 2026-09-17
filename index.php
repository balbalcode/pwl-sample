<?php

// core runner, pls do not remove it.
if (PHP_SAPI === 'cli-server') {
    $requestedFile = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($requestedFile)) {
        return false;
    }
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';

// parser URL, this line will divide the uri into several parts
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base     = trim(dirname($_SERVER['SCRIPT_NAME']), '/');
$path     = trim(substr($uri, strlen($base) + 1), '/');
$segments = explode('/', $path);

define('BASE_URL', $base === '' ? '' : '/' . $base);

// this is sample guys, when ure accessing the yourwebsite.com/account/id-account-1/edit
// base will contain yourwebsite.com
// path will contain /account/id-account-1/edit
// segment will contain  [0] -> account, [1] -> id-account-1, [2] -> edit,

$noIdActions = ['create', 'store'];
$page = $segments[0] ?: ''; // set default controller u guys right here, so if ure accessing the root or base url, will calls that controller.

if (!isset($segments[1]) || $segments[1] === '') {
    $id     = null;
    $action = 'index';
} elseif (in_array($segments[1], $noIdActions, true)) {
    $id     = null;
    $action = $segments[1];
} else {
    $id     = $segments[1];
    $action = $segments[2] ?? "detail";
}

$method = $_SERVER['REQUEST_METHOD'];

$controllerName = str_replace('-', '', ucwords($page, '-'));
$controller     = __DIR__ . '/controllers/' . $controllerName . '.php';

if (file_exists($controller)) {
    require_once $controller;
} else {
    http_response_code(404);
    echo '404 - Page not found';
}
