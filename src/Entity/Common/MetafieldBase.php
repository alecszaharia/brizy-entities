<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity\Common;

use Brizy\Bundle\EntitiesBundle\Entity\Metafield;
use Brizy\Bundle\EntitiesBundle\Entity\MetafieldInt;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

abstract class MetafieldBase implements MetaFieldTypeInterface
{
    use TimestampableEntity;

    /**
     * The unique numeric identifier for the Node
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Brizy\Bundle\EntitiesBundle\Entity\Metafield")
     * @ORM\JoinColumn(name="metafield_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected Metafield $metafield;

    /**
     * @var int
     *
     * @ORM\Column(name="entity_id", type="integer", unique=false)
     */
    protected $entity_id;



    /**
     * @var string
     *
     * @ORM\Column(name="value", type="integer" )
     */
    protected $value;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MetafieldInt
     */
    public function setId(int $id): MetafieldInt
    {
        $this->id = $id;
        return $this;
    }


    public function getMetafield(): Metafield
    {
        return $this->metafield;
    }

    /**
     * @param $metafield
     *
     * @return $this
     */
    public function setMetafield(Metafield $metafield): self
    {
        $this->metafield = $metafield;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntityId(): ?int
    {
        return $this->entity_id;
    }

    /**
     * @param $entity_id
     *
     * @return $this
     */
    public function setEntityId($entity_id): self
    {
        $this->entity_id = $entity_id;

        return $this;
    }
}
