<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BrizyEntitiesBundleExtension extends Extension implements PrependExtensionInterface
{
    final public const ALIAS_NAME = 'brizy_entities';

    final public const DOCTRINE_MAPPING = 'brizy_entities.persistence.doctrine.mapping';

    final public const DOCTRINE_MANAGER = 'brizy_entities.persistence.doctrine.entity_manager';

    public function getAlias()
    {
        return 'brizy_entities';
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $config = $this->processConfiguration(new Configuration(), $configs);
    }

    public function prepend(ContainerBuilder $container)
    {
        $mainConfig = $container->getExtensionConfig($this->getAlias());

        foreach ($container->getExtensions() as $name => $extension) {
            if ('trikoder_oauth2' === $name) {
                $trikoderConfig = $container->getExtensionConfig($name);
                $this->prependTrikoderConfiguration($container, $mainConfig[0], $trikoderConfig[0]);
            }
        }
    }

    private function prependTrikoderConfiguration(ContainerBuilder $container, $mainConfig, $trikoderConfig)
    {
        $managerName = $mainConfig['persistence']['doctrine']['entity_manager']['name'];
        $scopes = $mainConfig['persistence']['oauth2']['scopes'];
        $container->setParameter(self::DOCTRINE_MAPPING, true);
        $container->setParameter(self::DOCTRINE_MANAGER, $managerName);

        $trikoderConfig = [
            'authorization_server' => [
                'private_key' => '%env(multiline:resolve:OAUTH2_PRIVATE_KEY_PATH)%',
                'private_key_passphrase' => '%env(OAUTH2_KEY_PASSPHRASE)%',
                'encryption_key' => '%env(OAUTH2_ENCRYPTION_KEY)%',
                'access_token_ttl' => '%env(OAUTH2_ACCESS_TOKEN_TTL)%',
                'refresh_token_ttl' => '%env(OAUTH2_REFRESH_TOKEN_TTL)%',
            ],
            'resource_server' => ['public_key' => '%env(multiline:resolve:OAUTH2_PUBLIC_KEY_PATH)%'],
            'scopes' => $scopes,
            'persistence' => ['doctrine' => ['entity_manager' => $managerName]],
        ];

        $container->prependExtensionConfig('trikoder_oauth2', $trikoderConfig);
    }
}
