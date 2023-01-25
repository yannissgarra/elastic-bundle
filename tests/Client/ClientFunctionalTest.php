<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\Test\Client;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;
use Webmunkeez\ElasticBundle\Client\ElasticClientInterface;
use Webmunkeez\ElasticBundle\Test\Fixture\TestBundle\Model\Post;
use Webmunkeez\ElasticBundle\Test\Fixture\TestBundle\Repository\PostRepository;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class ClientFunctionalTest extends KernelTestCase
{
    private ElasticClientInterface $elasticClient;

    private PostRepository $postRepository;

    protected function setUp(): void
    {
        $this->elasticClient = static::getContainer()->get(ElasticClientInterface::class);

        $this->postRepository = new PostRepository();
    }

    public function testShouldSucceed(): void
    {
        // bulk -----

        $params = ['body' => []];

        foreach ($this->postRepository->findAll() as $post) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'post',
                    '_id' => $post->getId(),
                ],
            ];

            $params['body'][] = ['title_fr' => $post->getTitle()];
        }

        $this->elasticClient->bulk($params);

        $this->elasticClient->refreshIndices(['index' => 'post']);

        // get -----

        $params = [
            'index' => 'post',
            'id' => $this->postRepository->findOne(Uuid::fromString(PostRepository::DATA[0]['id']))->getId(),
        ];

        $response = $this->elasticClient->get($params);

        $this->assertTrue(Uuid::fromString($response['_id'])->equals(Uuid::fromString(PostRepository::DATA[0]['id'])));

        // mget -----

        $params = [
            'index' => 'post',
            'body' => ['ids' => array_values(array_map(fn (Post $post): Uuid => $post->getId(), $this->postRepository->findAll()))],
        ];

        $response = $this->elasticClient->mget($params);

        $this->assertCount(count($this->postRepository->findAll()), $response['docs']);

        // search -----

        $params = [
            'index' => 'post',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'ids' => [
                                'values' => array_values(array_map(fn (Post $post): Uuid => $post->getId(), $this->postRepository->findAll())),
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->elasticClient->search($params);

        $this->assertSame(count($this->postRepository->findAll()), $response['hits']['total']['value']);

        // delete -----

        foreach ($this->postRepository->findAll() as $post) {
            $this->elasticClient->delete(['index' => 'post', 'id' => $post->getId()]);
        }

        $this->elasticClient->refreshIndices(['index' => 'post']);

        $params = [
            'index' => 'post',
            'body' => [
                'query' => [
                    'bool' => [
                        'filter' => [
                            'ids' => [
                                'values' => array_values(array_map(fn (Post $post): Uuid => $post->getId(), $this->postRepository->findAll())),
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->elasticClient->search($params);

        $this->assertSame(0, $response['hits']['total']['value']);
    }
}
