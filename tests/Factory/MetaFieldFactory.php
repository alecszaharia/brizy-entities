<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\TestFactory;

use Brizy\Bundle\EntitiesBundle\Entity\Metafield;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainerInterface;

class MetaFieldFactory implements FactoryInterface
{
    public const NAME = 'name';
    public const TYPE = 'type';
    public const PROJECT = 'project';
    public const VALUE = 'value';
    public const WITHOUT_VALUE = 'withoutValue';

    public static function create(TestContainerInterface $container, array $params = [])
    {
        $metafield = new Metafield();
        $project = $params[self::PROJECT] ?? DataFactory::create($container);
        $type = $params[self::TYPE] ?? $container->faker()->randomElement(Metafield::TYPES);
        $metafield->setName($params[self::NAME] ?? $container->faker()->word);
        $metafield->setType($type);
        $metafield->setNode($project->getNode());
        $container->manager()->persist($metafield);
        if (!isset($params[self::WITHOUT_VALUE])) {
            if (Metafield::TYPE_VARCHAR === $type) {
                $metafieldValue = MetaFieldVarcharValueFactory::create($container, [
                    MetaFieldVarcharValueFactory::PROJECT => $project,
                    MetaFieldVarcharValueFactory::METAFIELD => $metafield,
                    self::VALUE => $params[self::VALUE] ?? null,
                ]);
            } elseif (Metafield::TYPE_INT === $type) {
                $metafieldValue = MetaFieldIntValueFactory::create($container, [
                    MetaFieldIntValueFactory::PROJECT => $project,
                    MetaFieldIntValueFactory::METAFIELD => $metafield,
                    self::VALUE => $params[self::VALUE] ?? null,
                ]);
            } else {
                $metafieldValue = MetaFieldTextValueFactory::create($container, [
                    MetaFieldTextValueFactory::PROJECT => $project,
                    MetaFieldTextValueFactory::METAFIELD => $metafield,
                    self::VALUE => $params[self::VALUE] ?? null,
                ]);
            }
            $metafield->setValue($metafieldValue->getValue());
        }

        return $metafield;
    }
}
