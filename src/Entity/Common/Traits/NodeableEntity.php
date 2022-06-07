<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity\Common\Traits;

use Brizy\Bundle\EntitiesBundle\Entity\Node;
use Doctrine\ORM\Mapping as ORM;

trait NodeableEntity
{
    /**
     * @var Node
     */
    #[ORM\ManyToOne(targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\Node::class, fetch: 'EAGER')]
    #[ORM\JoinColumn(name: 'node_id', referencedColumnName: 'id', onDelete: 'CASCADE', nullable: false)]
    protected $node;

    /**
     * @return Node
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return $this
     */
    public function setNode(Node $node)
    {
        $this->node = $node;

        return $this;
    }
}
