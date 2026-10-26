<?php

// catch any real error (not our own 404s) and show it as a meme instead of
// a raw PHP fatal-error dump
set_exception_handler(function (Throwable $exception) {
    http_response_code(500);
    require __DIR__ . '/views/errors/crash.php';
});

// core runner, pls do not remove it.
if (PHP_SAPI === 'cli-server') {
    $requestedFile = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($requestedFile)) {
        return false;
    }
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Loader.php';

// parser URL, this line will divide the uri into several parts
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// SCRIPT_NAME can come back with backslashes (Windows) or as a bare
// filename with no leading slash -- normalize it before computing the
// base path, and bail out to '' (no subfolder) if it still looks wrong
// instead of producing a broken BASE_URL.
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$base       = trim(dirname($scriptName), '/');

if ($base === '.' || strpos($base, ':') !== false) {
    $base = '';
}

if ($base !== '' && ($uri === '/' . $base || strpos($uri, '/' . $base . '/') === 0)) {
    $uri = substr($uri, strlen('/' . $base));
}

$path     = trim($uri, '/');
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
    $action = $segments[2] ?? 'detail';
}

$method = $_SERVER['REQUEST_METHOD'];

// actions that must come from a form submission (POST), not a plain link/URL (GET)
$postOnlyActions = ['store', 'update', 'delete'];
$expectsPost     = in_array($action, $postOnlyActions, true);

$controllerFile = null;
$controllerName = null;
$squashedPage   = strtolower(str_replace('-', '', $page));

foreach (glob(__DIR__ . '/controllers/*.php') as $candidate) {
    $candidateName = basename($candidate, '.php');
    if (strtolower($candidateName) === $squashedPage) {
        $controllerFile = $candidate;
        $controllerName = $candidateName;
        break;
    }
}

if ($controllerFile === null) {
    http_response_code(404);
    echo '404 - Page not found';
    exit;
}

$canonicalPage = strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $controllerName));

if ($page !== $canonicalPage) {
    $rest     = implode('/', array_slice($segments, 1));
    $location = BASE_URL . '/' . $canonicalPage . ($rest !== '' ? '/' . $rest : '');
    if (!empty($_SERVER['QUERY_STRING'])) {
        $location .= '?' . $_SERVER['QUERY_STRING'];
    }
    header('Location: ' . $location, true, $method === 'GET' ? 301 : 307);
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    http_response_code(404);
    echo '404 - Page not found';
    exit;
}

if (($expectsPost && $method !== 'POST') || (!$expectsPost && $method !== 'GET')) {
    http_response_code(404);
    echo '404 - Action not found';
    exit;
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo '404 - Action not found';
    exit;
}

if ($id === null) {
    $controller->$action();
} else {
    $controller->$action($id);
}
