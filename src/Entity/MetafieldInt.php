<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Brizy\Bundle\EntitiesBundle\Entity\Common\MetafieldBase;
use Brizy\Bundle\EntitiesBundle\Repository\MetafieldIntRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Table(name="metafield__int", uniqueConstraints={@UniqueConstraint(columns={"entity_id","metafield_id"})})
 * @ORM\Entity(repositoryClass=MetafieldIntRepository::class)
 */
class MetafieldInt extends MetafieldBase
{
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function setValue($value): MetafieldInt
    {
        $this->value = $value;

        return $this;
    }

}
