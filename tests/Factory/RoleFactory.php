<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Tests\Factory;

use Brizy\Bundle\EntitiesBundle\Entity\Role;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainer;
use Faker\Generator;

class RoleFactory implements FactoryInterface
{
    public const NAME = 'name';
    public const CREATE = 'create';
    public const READ = 'read';
    public const UPDATE = 'update';
    public const DELETE = 'delete';

    /**
     * @return Role
     *
     * @throws \Exception
     */
    public static function create(TestContainer $container, array $params = [], Generator $faker = null)
    {
        if (!isset($params[self::NAME])) {
            throw new \Exception('Please provide a role name');
        }

        $role = new Role();
        $role->setUid($faker->uuid);
        $role->setName($params[self::NAME]);
        $role->setCreateAction($params[self::CREATE] ?? true);
        $role->setUpdateAction($params[self::UPDATE] ?? true);
        $role->setDeleteAction($params[self::DELETE] ?? true);
        $role->setReadAction($params[self::READ] ?? true);
        $role->setNode(NodeFactory::create($container));

        $container->manager()->persist($role);
        $container->manager()->flush();

        return $role;
    }
}
