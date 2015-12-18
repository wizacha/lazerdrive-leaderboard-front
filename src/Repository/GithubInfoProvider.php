<?php

namespace Leaderboard\Repository;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Leaderboard\Player;

/**
 * Decorate another repository to add GitHub information to the user (company and avatar).
 */
class GithubInfoProvider implements PlayerRepository
{
    const API_URL = 'https://api.github.com/users/';

    /**
     * @var PlayerRepository
     */
    private $decoratedRepository;

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(PlayerRepository $decoratedRepository, Client $httpClient)
    {
        $this->decoratedRepository = $decoratedRepository;
        $this->httpClient = $httpClient;
    }

    public function getTopPlayers() : array
    {
        $players = $this->decoratedRepository->getTopPlayers();

        return array_map(function (Player $player) {
            $githubInfo = $this->getGithubInfo($player->getName());

            if (!empty($githubInfo)) {
                $player->setCompany((string) $githubInfo['company']);
                $player->setAvatarUrl((string) $githubInfo['avatar_url']);
            }

            return $player;
        }, $players);
    }

    /**
     * Fetch GitHub data for the given user name.
     *
     * If no data was found, an empty array is returned.
     *
     * @return array
     */
    private function getGithubInfo(string $username) : array
    {
        try {
            $response = $this->httpClient->request('GET', self::API_URL . $username);
        } catch (ClientException $e) {
            // 404
            return [];
        } catch (\Exception $e) {
            return [];
        }

        return json_decode($response->getBody(), true);
    }
}
