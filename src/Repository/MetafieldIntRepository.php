<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Repository;

use Brizy\Bundle\EntitiesBundle\Entity\MetafieldInt;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MetafieldInt|null find($id, $lockMode = null, $lockVersion = null)
 * @method MetafieldInt|null findOneBy(array $criteria, array $orderBy = null)
 * @method MetafieldInt[]    findAll()
 * @method MetafieldInt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MetafieldIntRepository extends MetafieldValueRepository
{
    public function __construct(Registry $registry)
    {
        parent::__construct($registry, MetafieldInt::class);
    }
}
