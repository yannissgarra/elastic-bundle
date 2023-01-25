<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('webmunkeez_elastic');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('hosts')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->scalarPrototype()->end()
                ->end()
                ->arrayNode('indices')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->variablePrototype()->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
