<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\Client;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ElasticClient implements ElasticClientInterface
{
    private array $elasticHosts;

    public function __construct(array $elasticHosts)
    {
        $this->elasticHosts = $elasticHosts;
    }

    public function get(array $params = [])
    {
        return $this->getClient()->get($params);
    }

    public function mget(array $params = [])
    {
        return $this->getClient()->mget($params);
    }

    public function search(array $params = [])
    {
        return $this->getClient()->search($params);
    }

    public function bulk(array $params = [])
    {
        return $this->getClient()->bulk($params);
    }

    public function delete(array $params = [])
    {
        return $this->getClient()->delete($params);
    }

    public function createIndices(array $params = [])
    {
        return $this->getClient()->indices()->create($params);
    }

    public function deleteIndices(array $params = [])
    {
        return $this->getClient()->indices()->delete($params);
    }

    public function openIndices(array $params = [])
    {
        return $this->getClient()->indices()->open($params);
    }

    public function closeIndices(array $params = [])
    {
        return $this->getClient()->indices()->close($params);
    }

    public function putIndicesSettings(array $params = [])
    {
        return $this->getClient()->indices()->putSettings($params);
    }

    public function putIndicesMapping(array $params = [])
    {
        return $this->getClient()->indices()->putMapping($params);
    }

    public function refreshIndices(array $params = [])
    {
        return $this->getClient()->indices()->refresh($params);
    }

    private function getClient(): Client
    {
        return ClientBuilder::create()
            ->setHosts($this->elasticHosts)
            ->build();
    }
}
