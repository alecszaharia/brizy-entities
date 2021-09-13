<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Repository;

use Brizy\Bundle\EntitiesBundle\Entity\ProjectAccessTokenMap;
use Doctrine\Persistence\ManagerRegistry;

class ProjectAccessTokenMapRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectAccessTokenMap::class);
    }
}
