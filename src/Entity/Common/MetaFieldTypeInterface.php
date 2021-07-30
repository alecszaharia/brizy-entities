<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity\Common;

use Brizy\Bundle\EntitiesBundle\Entity\Metafield;

interface MetaFieldTypeInterface
{
    public function getId(): ?int;

    public function getMetafield(): Metafield;

    public function setMetafield(Metafield $metafield): self;

    public function getEntityId(): ?int;

    public function setEntityId(int $entity_id): self;

    public function setValue($value);

    public function getValue();
}
