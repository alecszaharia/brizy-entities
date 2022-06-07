<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\TestFactory;

use Brizy\Bundle\EntitiesBundle\Entity\Node;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainerInterface;

class NodeFactory implements FactoryInterface
{
    public const NAME = 'name';

    public const SLUG = 'slug';

    public const CLASS_NAME = 'class_name';

    public static function create(TestContainerInterface $container, array $params = [])
    {
        $node = new Node();
        $node->setName($params[self::NAME] ?? $container->faker()->unique()->words(5, true));
        $node->setSlug($params[self::SLUG] ?? $container->faker()->unique()->slug);
        $node->setEntityClass($params[self::CLASS_NAME] ?? 'Void');
        $node->setIsFile(false);
        $node->setCreatedAt(new \DateTime());
        $node->setUpdatedAt(new \DateTime());

        $container->manager()->persist($node);

        return $node;
    }
}
