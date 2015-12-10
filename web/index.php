<?php

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

require __DIR__ . '/../vendor/autoload.php';

$container = new \Slim\Container([
    'settings' => [
        'displayErrorDetails' => true,
    ],
]);
$container['view'] = function ($c) {
    $view = new Twig(__DIR__ . '/../res/views', [
        'cache' => false,
    ]);
    $view->addExtension(new TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));

    return $view;
};

$app = new \Slim\App($container);

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) : ResponseInterface {
    $db = new PDO('mysql:host=localhost;dbname=lazerdrive', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);

    $scores = $db->query('SELECT player, score FROM highscore ORDER BY score DESC LIMIT 20')->fetchAll(PDO::FETCH_ASSOC);

    return $this->view->render($response, 'index.twig', [
        'scores' => $scores,
    ]);
});

$app->run();
