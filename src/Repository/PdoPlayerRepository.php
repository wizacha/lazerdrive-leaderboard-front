<?php

namespace Leaderboard\Repository;

use Leaderboard\Player;
use PDO;

class PdoPlayerRepository implements PlayerRepository
{
    public function getTopPlayers() : array
    {
        $db = new PDO('mysql:host=127.0.0.1;dbname=lazerdrive;charset=utf8', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);

        $statement = $db->query('SELECT player, score, online FROM highscore ORDER BY score DESC LIMIT 20');
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function (array $playerData) {
            return new Player($playerData['player'], $playerData['score'], $playerData['online']);
        }, $data);
    }
}
