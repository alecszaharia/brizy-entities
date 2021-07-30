<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\LogEntry;

/**
 * @ORM\Table(
 *     name="revision",
 *     indexes={
 *      @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *      @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Brizy\Bundle\EntitiesBundle\Repository\RevisionRepository")
 */
class Revision extends LogEntry
{
    /**
     * @var int
     *
     * @ORM\Column(name="object_id", type="integer", nullable=true)
     */
    protected $objectId;
}
