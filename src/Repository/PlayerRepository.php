<?php

namespace Leaderboard\Repository;

use Leaderboard\Player;

interface PlayerRepository
{
    /**
     * @return Player[]
     */
    public function getTopPlayers() : array;

    /**
     * @return Player[]
     */
    public function getPlayersOnline() : array;
}
