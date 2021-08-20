<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\TestFactory;

use Brizy\Bundle\EntitiesBundle\Entity\Data;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainerInterface;

class DataFactory implements FactoryInterface
{
    public const AUTHOR = 'author';
    public const NODE = 'node';

    public static function create(TestContainerInterface $container, array $params = [])
    {
        $author = $params[self::AUTHOR] ?? UserFactory::create($container);

        $data = new Data();
        $data->setHashId(md5(random_bytes(16)));
        $data->setUid(md5(random_bytes(16)));
        $data->setNode($params[self::NODE] ?? NodeFactory::create($container, [NodeFactory::SLUG => 'project']));
        $data->setAuthorId($author->getId());
        $data->setUser($author);
        $data->setCreatedAt(new \DateTime());
        $data->setUpdatedAt(new \DateTime());

        $containerData = new Data();
        $containerData->setHashId(md5(random_bytes(16)));
        $containerData->setUid(md5(random_bytes(16)));
        $containerData->setNode(NodeFactory::create($container));
        $containerData->setAuthorId($author->getId());
        $containerData->setCreatedAt(new \DateTime());
        $containerData->setUpdatedAt(new \DateTime());
        $data->setParent($containerData);

        $container->manager()->persist($containerData);
        $container->manager()->persist($data);

        DataUserRoleFactory::create(
            $container,
            [DataUserRoleFactory::CONTAINER => $containerData, DataUserRoleFactory::USER => $author]
        );

        return $data;
    }
}
