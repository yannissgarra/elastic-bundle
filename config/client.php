<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\ElasticBundle\Client\ElasticClient;
use Webmunkeez\ElasticBundle\Client\ElasticClientInterface;
use Webmunkeez\ElasticBundle\PHPUnit\ElasticClient as PHPUnitElasticClient;

return function (ContainerConfigurator $container) {
    $container->services()
        ->set(ElasticClient::class)
            ->args([param('webmunkeez_elastic.hosts')])

        ->set(ElasticClientInterface::class)

        ->alias(ElasticClientInterface::class, ElasticClient::class);

    if ('test' === $container->env()) {
        $container->services()
            ->set(PHPUnitElasticClient::class)
                ->decorate(ElasticClient::class)
                ->args([service('.inner')])

            ->alias(ElasticClientInterface::class, PHPUnitElasticClient::class);
    }
};
