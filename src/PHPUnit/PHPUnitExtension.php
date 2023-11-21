<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\PHPUnit;

use PHPUnit\Runner\AfterTestHook;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class PHPUnitExtension implements AfterTestHook
{
    public function executeAfterTest(string $test, float $time): void
    {
        ElasticClient::reset();
    }
}
