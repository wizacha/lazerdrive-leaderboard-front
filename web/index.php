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
$container['debug'] = true;
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

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
    $players = [
        [
            'name' => 'Jawad',
            'score' => 384,
        ],
        [
            'name' => 'Raoul',
            'score' => 302,
        ],
        [
            'name' => 'Thierry',
            'score' => 230,
        ],
        [
            'name' => 'Stéphane',
            'score' => 82,
        ],
    ];
    return $this->view->render($response, 'index.twig', [
        'players' => $players,
    ]);
});

$app->run();
