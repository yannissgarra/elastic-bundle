<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\PHPUnit;

use Webmunkeez\ElasticBundle\Client\ElasticClientInterface;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ElasticClient implements ElasticClientInterface
{
    private ElasticClientInterface $decorated;

    private static bool $mock = false;

    public function __construct(ElasticClientInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function get(array $params = [])
    {
        return $this->decorated->get($params);
    }

    public function mget(array $params = [])
    {
        return $this->decorated->mget($params);
    }

    public function search(array $params = [])
    {
        return $this->decorated->search($params);
    }

    public function bulk(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->bulk($params);
    }

    public function delete(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->delete($params);
    }

    public function createIndices(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->createIndices($params);
    }

    public function deleteIndices(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->deleteIndices($params);
    }

    public function openIndices(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->openIndices($params);
    }

    public function closeIndices(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->closeIndices($params);
    }

    public function putIndicesSettings(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->putIndicesSettings($params);
    }

    public function putIndicesMapping(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->putIndicesMapping($params);
    }

    public function refreshIndices(array $params = [])
    {
        if (true === self::$mock) {
            return;
        }

        return $this->decorated->refreshIndices($params);
    }

    public static function setMock(bool $mock): void
    {
        self::$mock = $mock;
    }

    public function mock(bool $mock): void
    {
        self::setMock($mock);
    }
}
