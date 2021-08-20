<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\TestFactory;

use Brizy\Bundle\EntitiesBundle\Entity\User;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainerInterface;
use Faker\Generator;

class UserFactory implements FactoryInterface
{
    public const LOCKED = 'locked';

    public static function create(TestContainerInterface $container, array $params = [], Generator $faker = null): User
    {
        $application = ApplicationFactory::create($container);

        $user = new User();
        $user->setApplication($application);
        $user->setUserRemoteId(random_int(1, 999));
        $user->setNode(
            NodeFactory::create(
                $container,
                [NodeFactory::SLUG => 'user', NodeFactory::NAME => 'User', NodeFactory::CLASS_NAME => User::class]
            )
        );
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        $user->setLocked($params[self::LOCKED] ?? false);
        $user->setApproved(true);

        $container->manager()->persist($user);
        $container->manager()->flush();

        return $user;
    }
}
