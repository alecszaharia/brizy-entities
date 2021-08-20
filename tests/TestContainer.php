<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Tests;

use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;

class TestContainer implements TestContainerInterface
{
    private ObjectManager $manager;
    private Faker $faker;

    /**
     * TestContainer constructor.
     */
    public function __construct(ObjectManager $manager, ?Faker $faker = null)
    {
        $this->manager = $manager;
        $this->faker = $faker ?: FakerFactory::create();
    }

    public function manager(): ObjectManager
    {
        return $this->manager;
    }

    public function faker(): Faker
    {
        return $this->faker;
    }

    public function repository($class)
    {
        return $this->manager()->getRepository($class);
    }

    public function flushManager()
    {
        $this->manager()->flush();
        $this->manager()->clear();
        $configuration = $this->manager()->getConfiguration();
        $configuration->getQueryCacheImpl()->flushAll();
        $configuration->getResultCacheImpl()->flushAll();
    }
}
