<?php

use GuzzleHttp\Client;
use Leaderboard\Repository\GithubInfoProvider;
use Leaderboard\Repository\PdoPlayerRepository;
use Leaderboard\Repository\PlayerRepository;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
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
$container['http_client'] = function () {
    return new Client();
};
$container['db'] = function () {
    $db = new PDO('mysql:host=127.0.0.1;dbname=lazerdrive;charset=utf8mb4', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    return $db;
};
$container['player_repository'] = function ($c) {
    $pdoRepository = new PdoPlayerRepository($c['db']);
    return new GithubInfoProvider($pdoRepository, $c['http_client']);
};

$app = new \Slim\App($container);

$app->get('/', function (Request $request, Response $response) use ($container) : Response {
    /** @var PlayerRepository $repository */
    $repository = $container['player_repository'];

    return $this->view->render($response, 'index.twig', [
        'players' => $repository->getTopPlayers(),
        'onlinePlayers' => $repository->getPlayersOnline(),
    ]);
});

$app->get('/deploy', function () {
    putenv('COMPOSER_HOME=/tmp/composerPhp');
    $result = shell_exec('cd .. && git pull 2>&1 && composer install --no-interaction --no-progress --no-dev 2>&1');
    return new TextResponse($result);
});

$app->run();
