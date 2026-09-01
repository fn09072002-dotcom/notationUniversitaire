<?php

require __DIR__ . '/../vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/') {
    require __DIR__ . '/../templates/home.php';
} else {
    http_response_code(404);
    require __DIR__ . '/../templates/error/404.php';
}
