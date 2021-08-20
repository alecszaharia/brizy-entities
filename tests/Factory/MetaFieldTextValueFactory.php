<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\TestFactory;

use Brizy\Bundle\EntitiesBundle\Entity\Metafield;
use Brizy\Bundle\EntitiesBundle\Entity\MetafieldText;
use Brizy\Bundle\EntitiesBundle\Tests\TestContainerInterface;

class MetaFieldTextValueFactory implements FactoryInterface
{
    public const METAFIELD = 'metafield';
    public const PROJECT = 'project';
    public const VALUE = 'value';

    public static function create(TestContainerInterface $container, array $params = [])
    {
        $metafieldValue = new MetafieldText();

        $project = $params[self::PROJECT] ?? DataFactory::create($container);
        $metafieldValue->setValue($params[self::VALUE] ?? $container->faker()->text);
        $metafieldValue->setMetafield($params[self::METAFIELD] ?? MetaFieldFactory::create($container, [self::PROJECT => $project, MetaFieldFactory::TYPE => Metafield::TYPE_TEXT]));
        $metafieldValue->setEntityId($project->getId());

        $metafieldValue->getMetafield()->setValue($metafieldValue->getValue());
        $container->manager()->persist($metafieldValue);

        return $metafieldValue;
    }
}
