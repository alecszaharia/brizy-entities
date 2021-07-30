<?php

namespace Brizy\Bundle\EntitiesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class BrizyEntitiesBundleExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $mainConfig = $container->getExtensionConfig($this->getAlias());

        foreach ($container->getExtensions() as $name => $extension) {
            if ($name === 'trikoder_oauth2') {
                $configs = $container->getExtensionConfig($name);
            }
            if ($name === 'brizy_oauth_bundle') {
                $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
                $loader->load('trikoder.yaml');
            }
        }
    }


    public function getAlias()
    {
        return 'brizy_entities';
    }
}