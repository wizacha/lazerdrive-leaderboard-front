<?php

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Zend\Diactoros\Response\TextResponse;

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
    $db = new PDO('mysql:host=127.0.0.1;dbname=lazerdrive;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);

    $scores = $db->query('SELECT player, score FROM highscore ORDER BY score DESC LIMIT 20')->fetchAll(PDO::FETCH_ASSOC);

    return $this->view->render($response, 'index.twig', [
        'scores' => $scores,
    ]);
});

$app->get('/deploy', function () {
    $result = shell_exec('cd .. && git pull 2>&1 && composer install --no-interaction --no-progress --no-dev 2>&1');
    return new TextResponse($result);
});

$app->run();
