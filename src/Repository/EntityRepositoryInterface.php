<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Repository;

interface EntityRepositoryInterface
{
    public function save($entity);

    public function remove($entity);

    public function persist($entity);

    public function flush();
}
