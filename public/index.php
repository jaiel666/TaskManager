<?php

use App\Controllers\TaskController;
use App\Router\Router;
use Doctrine\DBAL\DriverManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/../app/Views');
$twig = new Environment($loader);
$twig->addExtension(new DebugExtension());

$connectionParams = [
    'dbname' => 'task_manager',
    'user' => 'root',
    'password' => '123',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
];

$database = DriverManager::getConnection($connectionParams);
$taskController = new TaskController($database);

$routeInfo = Router::dispatch();

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = $handler;

        $response = $taskController->{$method}(...array_values($vars));

        switch (true) {
            case $response instanceof \App\ViewResponse:
                echo $twig->render($response->getViewName() . '.twig', $response->getData());
                break;
            case $response instanceof \App\RedirectResponse:
                header('Location: ' . $response->getLocation());
                break;
            default:
                break;
        }
        break;
}
