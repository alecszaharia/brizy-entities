<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use App\Security\OAuth\Grant\UserClientCredentialsGrant;
use App\Utils\Random;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Model\Grant;
use Trikoder\Bundle\OAuth2Bundle\Model\RedirectUri;
use Trikoder\Bundle\OAuth2Bundle\Model\Scope;
use Trikoder\Bundle\OAuth2Bundle\OAuth2Grants;

/**
 * Class UserSubscriber
 */
class UserSubscriber implements EventSubscriber
{
    /**
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $user = $eventArgs->getObject();

        if (!$user instanceof User) {
            return;
        }

        if (!$user->getCmsApiClient()) {
            $client = self::generateClient();
            $client->setActive(!$user->getLocked());
            $user->setCmsApiClient($client);
        }
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $user = $eventArgs->getObject();

        if (!$user instanceof User) {
            return;
        }

        if ($client = $user->getCmsApiClient()) {
            $client->setActive(!$user->getLocked());
        }
    }

    /**
     * @todo: This probably must be moved from here
     *
     * @description From thephpleague/oauth2-server:8.2.2 each oauth client  should have at least one redirect URI to be valid
     *              I added a random URL to pass the validation.
     *
     * @throws \Exception
     */
    public static function generateClient(): Client
    {
        $client = new Client(Random::generateOauthClientIdentifier(), Random::generateOauthClientSecret());
        $client->setScopes(
            new Scope('user')
        );
        $client->setGrants(
            new Grant(UserClientCredentialsGrant::USER_CLIENT_CREDENTIALS),
            new Grant(OAuth2Grants::REFRESH_TOKEN)
        );
        $client->setRedirectUris(new RedirectUri('https://unknown/'.md5((string) time()))); // random URL to pass the client validation

        return $client;
    }
}
