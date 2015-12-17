<?php

use Leaderboard\Repository\PdoPlayerRepository;
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
    $repository = new PdoPlayerRepository();

    return $this->view->render($response, 'index.twig', [
        'players' => $repository->getTopPlayers(),
    ]);
});

$app->get('/deploy', function () {
    putenv('COMPOSER_HOME=/tmp/composerPhp');
    $result = shell_exec('cd .. && git pull 2>&1 && composer install --no-interaction --no-progress --no-dev 2>&1');
    return new TextResponse($result);
});

$app->run();
