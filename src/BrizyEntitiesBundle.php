<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle;

use Brizy\Bundle\EntitiesBundle\DependencyInjection\BrizyEntitiesBundleExtension;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BrizyEntitiesBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new BrizyEntitiesBundleExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $this->configureDoctrineExtension($container);
    }

    private function configureDoctrineExtension(ContainerBuilder $container): void
    {
        $namespaces = ['Brizy\Bundle\EntitiesBundle\Entity'];
        $directories = [realpath(__DIR__.'/Entity')];
        $managerParameters = [BrizyEntitiesBundleExtension::DOCTRINE_MANAGER];
        $enabledParameter = BrizyEntitiesBundleExtension::DOCTRINE_MAPPING;
        $aliasMap = ['BrizyEntitiesBundle' => 'Brizy\Bundle\EntitiesBundle\Entity'];

        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createAnnotationMappingDriver(
                $namespaces,
                $directories,
                $managerParameters,
                $enabledParameter,
                $aliasMap
            ), \Symfony\Component\DependencyInjection\Compiler\PassConfig::TYPE_BEFORE_OPTIMIZATION, 0
        );
    }
}
