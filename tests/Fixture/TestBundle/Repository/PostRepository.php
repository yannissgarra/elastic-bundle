<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ElasticBundle\Test\Fixture\TestBundle\Repository;

use Symfony\Component\Uid\Uuid;
use Webmunkeez\ElasticBundle\Test\Fixture\TestBundle\Model\Post;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class PostRepository
{
    public const DATA = [
        ['id' => '3ba68645-d8a5-4c39-ab48-e0bb219414b0', 'title' => 'Post 1'],
        ['id' => 'c4648955-861b-44b9-b5dd-3f7c08dbef16', 'title' => 'Post 2'],
        ['id' => '0a018dd3-6605-489c-98bb-284265662bd9', 'title' => 'Post 3'],
        ['id' => 'fca4afbb-768a-4718-9309-201270603a2e', 'title' => 'Post 4'],
        ['id' => '007fa1b2-9b3a-48e1-a52e-58c2c9c87bdb', 'title' => 'Post 5'],
    ];

    /**
     * @var array<Post>
     */
    private array $posts;

    public function __construct()
    {
        // init values
        $this->posts = [];

        foreach (self::DATA as $row) {
            $this->posts[$row['id']] = (new Post())->setId(Uuid::fromString($row['id']))->setTitle($row['title']);
        }
    }

    /**
     * @return array<Post>
     */
    public function findAll(): array
    {
        return array_values($this->posts);
    }

    public function findOne(Uuid $id): ?Post
    {
        if (false === isset($this->posts[$id->toRfc4122()])) {
            return null;
        }

        return $this->posts[$id->toRfc4122()];
    }
}
