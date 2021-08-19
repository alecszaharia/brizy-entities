<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Tests\Factory;

use Brizy\Bundle\EntitiesBundle\Entity\Application;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainer;

class ApplicationFactory implements FactoryInterface
{
    public const DEFAULT_SCOPE = 'user';
    public const NAME = 'name';
    public const API_KEY = 'api_key';
    public const SECRET = 'secret';
    public const CLIENT_ID = 'cliend_id';
    public const SCOPE = 'scope';

    public static function create(TestContainer $container, array $params = []): Application
    {
        $application = new Application();
        $application->setName($params[self::NAME] ?? $container->faker()->word);
        $application->setApiKey($params[self::API_KEY] ?? md5(random_bytes(16)));
        $application->setSecret($params[self::SECRET] ?? md5(random_bytes(16)));
        $application->setClientId($params[self::SECRET] ?? md5(random_bytes(16)));
        $application->setScope($params[self::SCOPE] ?? self::DEFAULT_SCOPE);
        $container->manager()->persist($application);

        return $application;
    }
}
