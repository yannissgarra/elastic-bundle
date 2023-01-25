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
use Elastic\Elasticsearch\Endpoints\Indices;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
interface ElasticClientInterface
{
    /**
     * @see Client::get()
     **/
    public function get(array $params = []);

    /**
     * @see Client::mget()
     **/
    public function mget(array $params = []);

    /**
     * @see Client::search()
     **/
    public function search(array $params = []);

    /**
     * @see Client::bulk()
     **/
    public function bulk(array $params = []);

    /**
     * @see Client::delete()
     **/
    public function delete(array $params = []);

    /**
     * @see Indices::create()
     **/
    public function createIndices(array $params = []);

    /**
     * @see Indices::delete()
     **/
    public function deleteIndices(array $params = []);

    /**
     * @see Indices::open()
     **/
    public function openIndices(array $params = []);

    /**
     * @see Indices::close()
     **/
    public function closeIndices(array $params = []);

    /**
     * @see Indices::putSettings()
     **/
    public function putIndicesSettings(array $params = []);

    /**
     * @see Indices::putMapping()
     **/
    public function putIndicesMapping(array $params = []);

    /**
     * @see Indices::refresh()
     **/
    public function refreshIndices(array $params = []);
}
