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
     * @var bool
     */
    private $isOnline;

    /**
     * @var string
     */
    private $company;

    /**
     * @var string
     */
    private $color;

    /**
     * By default it's the default Gravatar.
     *
     * @var string
     */
    private $avatarUrl = 'http://www.gravatar.com/avatar/?d=identicon';

    public function __construct(string $name, int $highScore, bool $isOnline, string $company = '', string $color = '')
    {
        $this->name = $name;
        $this->highScore = $highScore;
        $this->isOnline = $isOnline;
        $this->company = $company;
        $this->color = $color;
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

    public function getColor() : string
    {
        return $this->color;
    }

    public function setColor($color) : string
    {
        $this->color = $color;
    }
}
