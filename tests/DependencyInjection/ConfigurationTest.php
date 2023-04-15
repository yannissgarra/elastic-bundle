<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\Test\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use Webmunkeez\ElasticBundle\DependencyInjection\Configuration;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ConfigurationTest extends TestCase
{
    public const DATA = [
        'hosts' => [
            'https://elastic-1.local',
            'https://elastic-2.local',
        ],
        'indices' => [
            'index-1' => [
                'settings' => [],
                'mappings' => [
                    'properties' => [],
                ],
            ],
            'index-2' => [
                'settings' => [],
                'mappings' => [
                    'properties' => [],
                ],
            ],
        ],
    ];

    public function testProcessShouldSucceed()
    {
        $processedConfig = (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_elastic' => self::DATA]);

        $this->assertEqualsCanonicalizing(self::DATA, $processedConfig);
    }

    public function testProcessWithoutConfigurationShouldThrowException()
    {
        $this->expectException(InvalidConfigurationException::class);

        (new Processor())->processConfiguration(new Configuration(), []);
    }

    public function testProcessWithoutHostsConfigurationShouldThrowException()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::DATA;
        unset($config['hosts']);

        (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_elastic' => $config]);
    }

    public function testProcessWithoutAtLeastOneHostConfigurationShouldThrowException()
    {
        $this->expectException(InvalidConfigurationException::class);

        $config = self::DATA;
        $config['hosts'] = [];

        (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_elastic' => $config]);
    }

    public function testProcessWithoutIndicesConfigurationShouldThrowException()
    {
        $config = self::DATA;
        unset($config['indices']);

        $processedConfig = (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_elastic' => $config]);

        $config['indices'] = [];

        $this->assertEqualsCanonicalizing($config, $processedConfig);
    }

    public function testProcessWithoutAtLeastOneIndexConfigurationShouldThrowException()
    {
        $config = self::DATA;
        $config['indices'] = [];

        $processedConfig = (new Processor())->processConfiguration(new Configuration(), ['webmunkeez_elastic' => $config]);

        $this->assertEqualsCanonicalizing($config, $processedConfig);
    }
}
