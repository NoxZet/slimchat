<?php

error_reporting(E_ALL ^ E_DEPRECATED);

$pdo = new \PDO("sqlite:../db/phpsqlite.db");
if (!$pdo) {
    throw new \Exception('Failed to create an SQLite connection');
}

require '../vendor/autoload.php';

$config = new \Slim\Container([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);
$router = new \App\Router(new \App\Database($pdo), $app = new \Slim\App($config));
$app->run();
