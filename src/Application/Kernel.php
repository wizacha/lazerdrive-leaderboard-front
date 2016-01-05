<?php

namespace Leaderboard\Application;

use DI\ContainerBuilder;
use Doctrine\Common\Cache\ApcuCache;

class Kernel extends \DI\Kernel\Kernel
{
    protected function getContainerCache()
    {
        if (function_exists('apcu_exists')) {
            return new ApcuCache();
        }

        return parent::getContainerCache();
    }

    protected function configureContainerBuilder(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->addDefinitions(__DIR__ . '/../../vendor/php-di/slim-bridge/src/config.php');
    }
}
