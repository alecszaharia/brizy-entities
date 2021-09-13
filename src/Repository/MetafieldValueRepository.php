<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Repository;

use Brizy\Bundle\EntitiesBundle\Entity\Common\MetafieldBase;
use Brizy\Bundle\EntitiesBundle\Entity\Metafield;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ManagerRegistry;

abstract class MetafieldValueRepository extends EntityRepository
{
    public function __construct(Registry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }

    public function getMetafieldValue(Metafield $metafield): ?MetafieldBase
    {
        return $this->findOneBy(['metafield' => $metafield]);
    }

    public function findAndRemoveByMetafield($metafield): void
    {
        $metafieldValue = $this->findOneBy(['metafield' => $metafield]);
        if ($metafieldValue) {
            $this->remove($metafieldValue);
        }
    }
}
