<?php

namespace Leaderboard\Repository;

use Leaderboard\Player;
use PDO;

class PdoPlayerRepository implements PlayerRepository
{
    /**
     * @var PDO
     */
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getTopPlayers() : array
    {
        $statement = $this->db->query('SELECT name, highscore, is_online, company, color FROM player WHERE name NOT LIKE "Player %" ORDER BY highscore DESC LIMIT 20');
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $this->createPlayers($data);
    }

    public function getPlayersOnline() : array
    {
        $statement = $this->db->query('SELECT name, highscore, is_online, company, color FROM player WHERE name NOT LIKE "Player %" AND is_online = 1 ORDER BY highscore DESC LIMIT 20');
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $this->createPlayers($data);
    }

    /**
     * @return Player[]
     */
    private function createPlayers(array $data) : array
    {
        return array_map(function (array $playerData) {
            return new Player($playerData['name'], $playerData['highscore'], $playerData['is_online'], $playerData['company'], $playerData['color']);
        }, $data);
    }
}
