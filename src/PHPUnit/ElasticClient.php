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

    private static self $instance;

    private static array $indexedDocuments = [];

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
        if (false === isset(self::$instance)) {
            self::$instance = $this;
        }

        foreach ($params['body'] as $body) {
            if (true === isset($body['index']['_index']) && true === isset($body['index']['_id'])) {
                $key = md5($body['index']['_index'].'.'.$body['index']['_id']);

                if (false === isset(self::$indexedDocuments[$key])) {
                    self::$indexedDocuments[$key] = ['index' => $body['index']['_index'], 'id' => $body['index']['_id']];
                }
            }
        }

        $params['refresh'] = true;

        return $this->decorated->bulk($params);
    }

    public function delete(array $params = [])
    {
        $params['refresh'] = true;

        return $this->decorated->delete($params);
    }

    public function createIndices(array $params = [])
    {
        return $this->decorated->createIndices($params);
    }

    public function deleteIndices(array $params = [])
    {
        return $this->decorated->deleteIndices($params);
    }

    public function openIndices(array $params = [])
    {
        return $this->decorated->openIndices($params);
    }

    public function closeIndices(array $params = [])
    {
        return $this->decorated->closeIndices($params);
    }

    public function putIndicesSettings(array $params = [])
    {
        return $this->decorated->putIndicesSettings($params);
    }

    public function putIndicesMapping(array $params = [])
    {
        return $this->decorated->putIndicesMapping($params);
    }

    public function refreshIndices(array $params = [])
    {
        return $this->decorated->refreshIndices($params);
    }

    public static function reset(): void
    {
        if (true === isset(self::$instance)) {
            foreach (self::$indexedDocuments as $key => $indexedDocument) {
                self::$instance->delete(['index' => $indexedDocument['index'], 'id' => $indexedDocument['id']]);

                unset(self::$indexedDocuments[$key]);
            }
        }
    }
}
