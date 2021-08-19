<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Tests\Factory;

use Brizy\Bundle\EntitiesBundle\Tests\TestContainer;

interface FactoryInterface
{
    public static function create(TestContainer $container, array $params = []);
}
