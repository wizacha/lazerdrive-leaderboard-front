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
    const CACHE_KEY_PREFIX = 'GithubInfoProvider.user.';

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

        return $this->addPlayerInformation($players);
    }

    public function getPlayersOnline() : array
    {
        $players = $this->decoratedRepository->getPlayersOnline();

        return $this->addPlayerInformation($players);
    }

    /**
     * @param Player[] $players
     * @return Player[]
     */
    private function addPlayerInformation(array $players) : array
    {
        return array_map(function (Player $player) {
            $githubInfo = $this->getCachedGithubInfo($player->getName());

            if (!empty($githubInfo)) {
                $player->setCompany((string) $githubInfo['company']);
                $player->setAvatarUrl((string) $githubInfo['avatar_url']);
            }

            return $player;
        }, $players);
    }

    /**
     * Fetch GitHub data from the cache, or from GitHub if not in cache.
     *
     * If no data was found, an empty array is returned.
     */
    private function getCachedGithubInfo(string $username) : array
    {
        if (! function_exists('apc_exists')) {
            return $this->getGithubInfo($username);
        }

        $key = self::CACHE_KEY_PREFIX . $username;

        if (apc_exists($key)) {
            return apc_fetch($key);
        }

        $data = $this->getGithubInfo($username);

        // Random TTL between 1 hour and 1 day to avoid refreshing all users at once
        $ttl = rand(3600, 3600*24);
        apc_store($key, $data, $ttl);

        return $data;
    }

    /**
     * Fetch GitHub data for the given user name.
     *
     * If no data was found, an empty array is returned.
     */
    private function getGithubInfo(string $username) : array
    {
        // Skip invalid GitHub usernames
        if (preg_match('/^[a-zA-Z\d-]+$/', $username) !== 1) {
            return [];
        }

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
