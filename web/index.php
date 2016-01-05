<?php

use Leaderboard\Application\Deployer;
use Leaderboard\Application\Kernel;
use Leaderboard\Repository\PlayerRepository;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\TextResponse;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App((new Kernel)->createContainer());

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
