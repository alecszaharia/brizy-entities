<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Tests\Factory;

use Brizy\Bundle\EntitiesBundle\Entity\Metafield;
use Brizy\Bundle\EntitiesBundle\Entity\MetafieldVarchar;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainer;

class MetaFieldVarcharValueFactory implements FactoryInterface
{
    public const METAFIELD = 'metafield';
    public const PROJECT = 'project';
    public const VALUE = 'value';

    public static function create(TestContainer $container, array $params = [])
    {
        $metafieldValue = new MetafieldVarchar();

        $project = $params[self::PROJECT] ?? DataFactory::create($container);
        $metafieldValue->setValue($params[self::VALUE] ?? $container->faker()->text(250));
        $metafieldValue->setMetafield($params[self::METAFIELD] ?? MetaFieldFactory::create($container, [self::PROJECT => $project, MetaFieldFactory::TYPE => Metafield::TYPE_VARCHAR]));
        $metafieldValue->setEntityId($project->getId());

        $metafieldValue->getMetafield()->setValue($metafieldValue->getValue());
        $container->manager()->persist($metafieldValue);

        return $metafieldValue;
    }
}
