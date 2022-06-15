<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Table(name: 'node')]
#[ORM\Entity(repositoryClass: \Brizy\Bundle\EntitiesBundle\Repository\NodeRepository::class)]
class Node
{
    use TimestampableEntity;

    final public const DEFAULT_ENTITY_CLASS_USER = User::class;

    /**
     * The unique numeric identifier for the Node
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private readonly int $id;

    #[ORM\OneToMany(targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\Node::class, mappedBy: 'parent', fetch: 'LAZY')]
    protected $children;

    #[ORM\ManyToOne(targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\Node::class, inversedBy: 'children', fetch: 'LAZY')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true)]
    protected $parent;

    /**
     * @var string
     */
    #[Assert\NotBlank]
    #[ORM\Column(name: 'name', type: 'string')]
    protected $name;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"},unique=false)
     */
    #[ORM\Column(name: 'slug', type: 'string')]
    protected $slug;

    /**
     * @var string
     */
    #[Assert\NotBlank]
    #[ORM\Column(name: 'entity_class', type: 'string')]
    protected $entity_class;

    /**
     * @var string
     */
    #[ORM\Column(name: 'default_role_uid', type: 'string', nullable: true)]
    protected $default_role_uid;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'is_file', type: 'boolean')]
    protected $is_file = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param $parent
     *
     * @return $this
     */
    public function setParent(Node $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return $this
     */
    public function setChildren(Node $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entity_class;
    }

    /**
     * @param $entity_class
     *
     * @return $this
     */
    public function setEntityClass($entity_class)
    {
        $this->entity_class = $entity_class;

        return $this;
    }

    public static function getDefaultEntityClasses()
    {
        return [
            self::DEFAULT_ENTITY_CLASS_USER,
        ];
    }

    /**
     * @return string
     */
    public function getDefaultRoleUid()
    {
        return $this->default_role_uid;
    }

    /**
     * @param $default_role_uid
     *
     * @return $this
     */
    public function setDefaultRoleUid($default_role_uid)
    {
        $this->default_role_uid = $default_role_uid;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsFile()
    {
        return $this->is_file;
    }

    /**
     * @param $is_file
     *
     * @return $this
     */
    public function setIsFile($is_file)
    {
        $this->is_file = $is_file;

        return $this;
    }

    public static function getDefaultsMetafieldsForFile()
    {
        return [
            [
                'name' => 'mime_type',
                'type' => Metafield::TYPE_VARCHAR,
            ],
            [
                'name' => 'file_size',
                'type' => Metafield::TYPE_INT,
            ],
        ];
    }
}
