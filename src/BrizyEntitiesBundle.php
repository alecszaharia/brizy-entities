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
        $directories = [realpath(__DIR__ . '/src/Entity')];
        $managerParameters = array();
        $enabledParameter = false;
        $aliasMap = array('BrizyEntitiesBundle' => 'Brizy\Bundle\EntitiesBundle\Entity');

        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createAnnotationMappingDriver(
                $namespaces,
                $directories,
                $managerParameters,
                $enabledParameter,
                $aliasMap
            )
        );
    }
}
