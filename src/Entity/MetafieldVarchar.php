<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Brizy\Bundle\EntitiesBundle\Entity\Common\MetafieldBase;
use Brizy\Bundle\EntitiesBundle\Repository\MetafieldBaseRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Brizy\Bundle\EntitiesBundle\Repository\MetafieldVarcharRepository;
/**
 * @ORM\Table(name="metafield__varchar", uniqueConstraints={@UniqueConstraint(columns={"entity_id","metafield_id"})})
 * @ORM\Entity(repositoryClass=MetafieldVarcharRepository::class)
 */
class MetafieldVarchar extends MetafieldBase
{
    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string" )
     */
    protected $value;

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
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
