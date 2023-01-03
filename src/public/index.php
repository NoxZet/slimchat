<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$pdo = new \PDO("sqlite:../db/phpsqlite.db");
if (!$pdo) {
    throw new \Exception('Failed to create an SQLite connection');
}

error_reporting(E_ALL ^ E_DEPRECATED);
require '../vendor/autoload.php';

$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});
$app->run();
