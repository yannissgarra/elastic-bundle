<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\PHPUnit;

use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class PHPUnitExtension implements BeforeFirstTestHook, AfterLastTestHook, AfterTestHook
{
    public function executeBeforeFirstTest(): void
    {
        ElasticClient::setMock(true);
    }

    public function executeAfterTest(string $test, float $time): void
    {
        ElasticClient::setMock(true);
    }

    public function executeAfterLastTest(): void
    {
        ElasticClient::setMock(false);
    }
}
