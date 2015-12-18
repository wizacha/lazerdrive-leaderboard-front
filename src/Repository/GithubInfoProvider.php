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
    const SECONDS_IN_DAY = 3600*24;

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

        apc_store($key, $data, self::SECONDS_IN_DAY);

        return $data;
    }

    /**
     * Fetch GitHub data for the given user name.
     *
     * If no data was found, an empty array is returned.
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
