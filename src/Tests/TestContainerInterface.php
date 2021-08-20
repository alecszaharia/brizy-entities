<?php

namespace Brizy\Bundle\EntitiesBundle\Tests;

use Doctrine\Persistence\ObjectManager;
use Faker\Generator as Faker;

interface TestContainerInterface
{
    public function manager(): ObjectManager;

    public function faker(): Faker;

    public function repository($class);

    public function flushManager();
}