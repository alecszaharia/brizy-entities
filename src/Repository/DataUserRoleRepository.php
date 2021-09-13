<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Repository;

use Brizy\Bundle\EntitiesBundle\Entity\DataUserRole;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataUserRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataUserRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataUserRole[]    findAll()
 * @method DataUserRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataUserRoleRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataUserRole::class);
    }
}
