<?php

use DI\ContainerBuilder;
use Doctrine\Common\Cache\ApcCache;
use Puli\Repository\Api\ResourceRepository;

require __DIR__ . '/../vendor/autoload.php';

$factoryClass = PULI_FACTORY_CLASS;
$factory = new $factoryClass();

/** @var ResourceRepository $repository */
$repository = $factory->createRepository();

$containerBuilder = new ContainerBuilder();

if (function_exists('apc_exists')) {
    $containerBuilder->setDefinitionCache(new ApcCache());
}

$containerBuilder->addDefinitions(__DIR__ . '/../vendor/php-di/slim-bridge/src/config.php');
$containerBuilder->addDefinitions($repository->get('/twig/config/config.php')->getFilesystemPath());
$containerBuilder->addDefinitions($repository->get('/app/config/config.php')->getFilesystemPath());
if ($repository->contains('/app/config/local.php')) {
    $containerBuilder->addDefinitions($repository->get('/app/config/local.php')->getFilesystemPath());
}
$containerBuilder->addDefinitions([
    ResourceRepository::class => $repository,
]);

return $containerBuilder->build();
