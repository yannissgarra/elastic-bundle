<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webmunkeez\ElasticBundle\Client\ElasticClientInterface;
use Webmunkeez\ElasticBundle\Console\Command\CreateIndexConsoleCommand;
use Webmunkeez\ElasticBundle\Console\Command\DeleteIndexConsoleCommand;
use Webmunkeez\ElasticBundle\Console\Command\UpdateIndexConsoleCommand;

return function (ContainerConfigurator $container) {
    $container->services()
        ->set(CreateIndexConsoleCommand::class)
            ->args([service(ElasticClientInterface::class), param('webmunkeez_elastic.indices')])
            ->tag('console.command', ['command' => 'elastic:index:create'])

        ->set(DeleteIndexConsoleCommand::class)
            ->args([service(ElasticClientInterface::class), param('webmunkeez_elastic.indices')])
            ->tag('console.command', ['command' => 'elastic:index:delete'])

        ->set(UpdateIndexConsoleCommand::class)
            ->args([service(ElasticClientInterface::class), param('webmunkeez_elastic.indices')])
            ->tag('console.command', ['command' => 'elastic:index:update']);
};
