<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Tests;

interface FactoryInterface
{
    public static function create(TestContainerInterface $container, array $params = []);
}
