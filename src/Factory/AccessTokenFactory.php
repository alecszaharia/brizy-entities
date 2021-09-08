<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Factory;

use Brizy\Bundle\EntitiesBundle\Entity\Data;
use Brizy\Bundle\EntitiesBundle\Entity\ProjectAccessTokenMap;
use Brizy\Bundle\EntitiesBundle\Entity\User;
use Doctrine\Persistence\ObjectManager;
use League\OAuth2\Server\CryptKey;
use Trikoder\Bundle\OAuth2Bundle\League\Entity\AccessToken as AccessTokenEntity;
use Trikoder\Bundle\OAuth2Bundle\League\Entity\Client as ClientEntity;
use Trikoder\Bundle\OAuth2Bundle\League\Entity\Scope as ScopeEntity;
use Trikoder\Bundle\OAuth2Bundle\Model\AccessToken;

class AccessTokenFactory
{
    public const PROJECT = 'project';
    public const USER = 'user';
    public const CLIENT = 'client_id';
    public const SCOPES = 'scopes';
    public const EXPIRE_DATE = 'expire';
    public const IDENTIFIER = 'identifier';

    /**
     * @return AccessToken
     *
     * @throws \Exception
     */
    public static function createForProject(ObjectManager $manager, array $params = [])
    {
        $clientId = $params[self::CLIENT];
        if (!$clientId) {
            throw new \InvalidArgumentException('An oauth client id should be provided');
        }

        /**
         * @var Data $project ;
         */
        $project = $params[self::PROJECT];

        if (!$project) {
            throw new \InvalidArgumentException('An project should be provided');
        }

        $user = $params[self::USER];

        if (!$user) {
            throw new \InvalidArgumentException('An User should be provided');
        }

        if (!isset($params[self::SCOPES])) {
            throw new \InvalidArgumentException('Please provide the token scope');
        }

        $accessToken = new AccessToken(
            $params[self::IDENTIFIER] ?? md5(random_bytes(32)),
            $params[self::EXPIRE_DATE] ?? (new \DateTimeImmutable())->modify('+1 day'),
            $clientId,
            (string) $user->getId(),
            $params[self::SCOPES]
        );

        $map = new ProjectAccessTokenMap($project, $accessToken);

        $manager->persist($accessToken);
        $manager->persist($map);

        return $accessToken;
    }

    /**
     * @return AccessToken
     *
     * @throws \Exception
     */
    public static function createForUser(ObjectManager $manager, array $params = [])
    {
        /**
         * @var User $user ;
         */
        $user = $params[self::USER];

        if (!$user) {
            throw new \InvalidArgumentException('An user should be provided');
        }

        $accessToken = new AccessToken(
            md5(random_bytes(32)),
            (new \DateTimeImmutable())->modify('+1 day'),
            $user->getCmsApiClient(),
            (string) $user->getId(),
            $params[self::SCOPES]
        );

        $manager->persist($accessToken);

        return $accessToken;
    }

    public static function generateJwtToken(AccessToken $accessToken, $keyPath): string
    {
        $clientEntity = new ClientEntity();
        $clientEntity->setIdentifier($accessToken->getClient()->getIdentifier());
        $clientEntity->setRedirectUri(array_map('strval', $accessToken->getClient()->getRedirectUris()));

        /**
         * This is for the case when the OAUTH2_XXXX_KEY_PATH keys are set as key constant not paths to files.
         */
        $keyPath = str_replace(["\n", '\n'], "\n", $keyPath);

        $accessTokenEntity = new AccessTokenEntity();
        $accessTokenEntity->setPrivateKey(new CryptKey($keyPath, null, false));
        $accessTokenEntity->setIdentifier($accessToken->getIdentifier());
        $accessTokenEntity->setExpiryDateTime($accessToken->getExpiry());
        $accessTokenEntity->setClient($clientEntity);
        $accessTokenEntity->setUserIdentifier($accessToken->getUserIdentifier());

        foreach ($accessToken->getScopes() as $scope) {
            $scopeEntity = new ScopeEntity();
            $scopeEntity->setIdentifier((string) $scope);

            $accessTokenEntity->addScope($scopeEntity);
        }

        return (string) $accessTokenEntity;
    }
}
