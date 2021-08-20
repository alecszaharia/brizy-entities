<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\TestFactory;

use Brizy\Bundle\EntitiesBundle\Entity\Metafield;
use Brizy\Bundle\EntitiesBundle\Entity\MetafieldInt;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainerInterface;

class MetaFieldIntValueFactory implements FactoryInterface
{
    public const METAFIELD = 'metafield';
    public const PROJECT = 'project';
    public const VALUE = 'value';

    public static function create(TestContainerInterface $container, array $params = [])
    {
        $metafieldValue = new MetafieldInt();

        $project = $params[self::PROJECT] ?? DataFactory::create($container);

        $metafieldValue->setValue($params[self::VALUE] ?? $container->faker()->numberBetween(1));
        $metafieldValue->setMetafield($params[self::METAFIELD] ?? MetaFieldFactory::create($container, [self::PROJECT => $project, MetaFieldFactory::TYPE => Metafield::TYPE_INT]));
        $metafieldValue->setEntityId($project->getId());

        $metafieldValue->getMetafield()->setValue($metafieldValue->getValue());
        $container->manager()->persist($metafieldValue);

        return $metafieldValue;
    }
}
