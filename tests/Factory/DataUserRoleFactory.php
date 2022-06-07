<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\TestFactory;

use Brizy\Bundle\EntitiesBundle\Entity\DataUserRole;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainerInterface;
use Exception;
use Faker\Generator;

class DataUserRoleFactory implements FactoryInterface
{
    public const USER = 'user';

    public const CONTAINER = 'container';

    public const ROLE_UID = 'role_uid';

    public const STATUS = 'status';

    /**
     * @return DataUserRole
     *
     * @throws Exception
     */
    public static function create(TestContainerInterface $container, array $params = [], Generator $faker = null)
    {
        if (!isset($params[self::CONTAINER])) {
            throw new Exception('Unable to create DataUserRole without a project instance');
        }

        if (!isset($params[self::USER])) {
            throw new Exception('Unable to create DataUserRole without a user instance');
        }

        $role = new DataUserRole();
        $role->setData($params[self::CONTAINER]);
        $role->setUser($params[self::USER]);
        $role->setRoleUid(
            $params[self::ROLE_UID] ?? RoleFactory::create(
                $container,
                [RoleFactory::NAME => 'Admin'],
                $container->faker()
            )->getUid()
        );
        $role->setStatus($params[self::STATUS] ?? DataUserRole::STATUS_APPROVED);

        $container->manager()->persist($role);

        return $role;
    }
}
