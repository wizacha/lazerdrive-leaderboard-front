<?php

namespace Leaderboard;

class Player
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $highScore;

    /**
     * @var string
     */
    private $isOnline;

    /**
     * @var string
     */
    private $company = '';

    /**
     * @var string
     */
    private $avatarUrl = '';

    public function __construct(string $name, int $highScore, bool $isOnline)
    {
        $this->name = $name;
        $this->highScore = $highScore;
        $this->isOnline = $isOnline;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getHighScore() : int
    {
        return $this->highScore;
    }

    public function isOnline() : bool
    {
        return $this->isOnline;
    }

    public function setCompany(string $company)
    {
        $this->company = $company;
    }

    public function getCompany() : string
    {
        return $this->company;
    }

    public function setAvatarUrl(string $avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;
    }

    public function getAvatarUrl() : string
    {
        return $this->avatarUrl;
    }
}
