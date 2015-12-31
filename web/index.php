<?php

use Interop\Container\ContainerInterface;
use Leaderboard\Application\Deployer;
use Leaderboard\Repository\PlayerRepository;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\TextResponse;

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../res/bootstrap.php';

$app = new \Slim\App($container);

$app->get('/', function (PlayerRepository $repository, Twig_Environment $twig) {
    return new HtmlResponse($twig->render('/app/views/index.twig', [
        'players' => $repository->getTopPlayers(),
        'onlinePlayers' => $repository->getPlayersOnline(),
    ]));
});

$app->get('/deploy', function (Deployer $deployer) {
    return new TextResponse($deployer->deploy());
});

$app->run();
