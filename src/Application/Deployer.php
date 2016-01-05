<?php

namespace Leaderboard\Application;

class Deployer
{
    /**
     * Deploy the latest version of the application.
     *
     * @return string Log result.
     */
    public function deploy() : string
    {
        putenv('COMPOSER_HOME=/tmp/composerPhp');

        return shell_exec('cd .. && git pull 2>&1 && composer install --no-interaction --no-progress --no-dev 2>&1');
    }
}
