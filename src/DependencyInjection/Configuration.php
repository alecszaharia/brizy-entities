<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(BrizyEntitiesBundleExtension::ALIAS_NAME);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->append($this->createPersistenceNode());

        return $treeBuilder;
    }

    private function createPersistenceNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('persistence');
        $node = $treeBuilder->getRootNode();

        $node
            ->performNoDeepMerging()
            ->children()
            // Doctrine persistence
                ->arrayNode('doctrine')
                    ->children()
                        ->arrayNode('entity_manager')
                            ->children()
                                ->scalarNode('name')
                                    ->isRequired()
                                    ->info('Entity manager name used for Brizy Entities [brizy_entities_manager]')
                                    ->cannotBeEmpty()
                                    ->defaultValue('brizy_entities_manager')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('oauth2')
                    ->isRequired()
                    ->children()
                        ->arrayNode('scopes')
                        ->prototype('scalar')
                        ->treatNullLike([])
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }

    private function createScopesNode(): NodeDefinition
    {
        $treeBuilder = new TreeBuilder('scopes');
        $node = $treeBuilder->getRootNode();

        $node
            ->info("Scopes that you wish to utilize in your application.\nThis should be a simple array of strings.")
            ->scalarPrototype()
            ->treatNullLike([])
        ;

        return $node;
    }
}
