<?php

use function DI\get;
use function DI\object;
use Leaderboard\Repository\GithubInfoProvider;
use Leaderboard\Repository\PdoPlayerRepository;
use Leaderboard\Repository\PlayerRepository;

return [

    // Slim settings
    'settings.displayErrorDetails' => true,

    PDO::class => object()
        ->constructor('mysql:host=127.0.0.1;dbname=lazerdrive;charset=utf8mb4', 'root', '', [])
        ->method('setAttribute', PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION),

    PlayerRepository::class => object(GithubInfoProvider::class)
        ->constructorParameter('decoratedRepository', get(PdoPlayerRepository::class)),

];
