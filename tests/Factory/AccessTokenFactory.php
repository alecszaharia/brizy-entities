<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Tests\Factory;

use Brizy\Bundle\EntitiesBundle\Tests\TestContainer;
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
    public static function createForProject(TestContainer $container, array $params = [])
    {
        return \Brizy\Bundle\EntitiesBundle\Factory\AccessTokenFactory::createForProject($container->manager(),$params);
    }

    /**
     * @return AccessToken
     *
     * @throws \Exception
     */
    public static function createForUser(TestContainer $container, array $params = [])
    {
        return \Brizy\Bundle\EntitiesBundle\Factory\AccessTokenFactory::createForUser($container->manager(),$params);
    }

    public static function generateJwtToken(AccessToken $accessToken): string
    {
        $keyPath = getenv(self::OAUTH2_PRIVATE_KEY_PATH);
        return \Brizy\Bundle\EntitiesBundle\Factory\AccessTokenFactory::generateJwtToken($accessToken,$keyPath);
    }
}
