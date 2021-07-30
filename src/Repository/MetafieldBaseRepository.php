<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Repository;

use Brizy\Bundle\EntitiesBundle\Entity\Common\MetafieldBase;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MetafieldBase|null find($id, $lockMode = null, $lockVersion = null)
 * @method MetafieldBase|null findOneBy(array $criteria, array $orderBy = null)
 * @method MetafieldBase[]    findAll()
 * @method MetafieldBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetafieldBaseRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MetafieldBase::class);
    }
}
