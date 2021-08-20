<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\TestFactory;

use Brizy\Bundle\EntitiesBundle\Tests\TestContainerInterface;
use Trikoder\Bundle\OAuth2Bundle\Model\AccessToken;

class AccessTokenFactory
{
    public const PROJECT = 'project';
    public const USER = 'user';
    public const CLIENT = 'client_id';
    public const SCOPES = 'scopes';
    public const EXPIRE_DATE = 'expire';
    public const IDENTIFIER = 'identifier';
    public const OAUTH2_PRIVATE_KEY_PATH = 'OAUTH2_PRIVATE_KEY_PATH';

    /**
     * @return AccessToken
     *
     * @throws \Exception
     */
    public static function createForProject(TestContainerInterface $container, array $params = [])
    {
        return \Brizy\Bundle\EntitiesBundle\Factory\AccessTokenFactory::createForProject($container->manager(),$params);
    }

    /**
     * @return AccessToken
     *
     * @throws \Exception
     */
    public static function createForUser(TestContainerInterface $container, array $params = [])
    {
        return \Brizy\Bundle\EntitiesBundle\Factory\AccessTokenFactory::createForUser($container->manager(),$params);
    }

    public static function generateJwtToken(AccessToken $accessToken,$keyPath=null): string
    {
        $key = $keyPath ?? getenv(self::OAUTH2_PRIVATE_KEY_PATH);
        return \Brizy\Bundle\EntitiesBundle\Factory\AccessTokenFactory::generateJwtToken($accessToken,$key);
    }
}
