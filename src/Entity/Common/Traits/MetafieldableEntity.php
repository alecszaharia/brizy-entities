<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity\Common\Traits;

trait MetafieldableEntity
{
    /**
     * @var array
     */
    protected $metafields = [];

    /**
     * @return array
     */
    public function getMetafields()
    {
        return $this->metafields;
    }

    /**
     * @param $metafields
     *
     * @return $this
     */
    public function setMetafields(array $metafields = null)
    {
        $this->updatedAt = new \DateTime();

        $this->metafields = $metafields;

        return $this;
    }

    /**
     * @return $this
     */
    public function addMetafields(array $metafields)
    {
        $this->updatedAt = new \DateTime();

        if (!$this->metafields) {
            $this->metafields = [];
        }

        $this->metafields = array_merge($this->metafields, $metafields);

        return $this;
    }
}
