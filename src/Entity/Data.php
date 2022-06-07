<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Brizy\Bundle\EntitiesBundle\Entity\Common\Traits\MetafieldableEntity;
use Brizy\Bundle\EntitiesBundle\Entity\Common\Traits\NodeableEntity;
use Brizy\Bundle\EntitiesBundle\Utils\GeneralUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\Loggable(logEntryClass="Brizy\Bundle\EntitiesBundle\Entity\Revision")
 */
#[UniqueEntity('uid')]
#[ORM\Table(name: 'data', indexes: [
    new ORM\Index(name: 'node_author', columns: ['node_id', 'author_id']),
    new ORM\Index(name: 'node_body_ref', columns: ['node_id', 'body_ref']),
    new ORM\Index(name: 'node_title', columns: ['node_id', 'title']),
    new ORM\Index(name: 'node_title_status', columns: ['node_id', 'title', 'status']),
])]
#[ORM\Entity(repositoryClass: \Brizy\Bundle\EntitiesBundle\Repository\DataRepository::class)]
class Data
{
    use TimestampableEntity;
    use MetafieldableEntity;
    use NodeableEntity;

    final public const NODE_STATUS_PUBLISH = 'publish';

    final public const DEFAULT_LANGUAGE = 'en';

    /**
     * The unique numeric identifier for the Node
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private readonly int $id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'uid', type: 'string', nullable: false, unique: true, options: ['collation' => 'utf8_bin'])]
    protected $uid;

    /**
     * @var string
     */
    #[ORM\Column(name: 'hash_id', type: 'string', nullable: true, unique: false, options: ['collation' => 'utf8_bin'])]
    protected $hash_id;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\Data::class, cascade: [
        'remove',
        'persist',
    ], fetch: 'LAZY')]
    protected $children;

    #[ORM\ManyToOne(targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\Data::class, inversedBy: 'children', fetch: 'LAZY', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE', nullable: true)]
    protected $parent;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     */
    #[Assert\Choice(callback: 'getValidLangCodes', strict: true)]
    #[ORM\Column(name: 'lang_code', type: 'string', length: 2, options: ['default' => 'en'])]
    protected $lang_code = self::DEFAULT_LANGUAGE;

    /**
     * @var int
     * @Gedmo\Versioned
     */
    #[ORM\Column(name: 'status', type: 'string', length: 20, nullable: true)]
    protected $status;

    /**
     * @var string
     * @Gedmo\Versioned
     */
    #[ORM\Column(name: 'title', type: 'string', nullable: true, options: ['collation' => 'utf8_bin'])]
    protected $title;

    /**
     * @var string
     * @Gedmo\Versioned
     */
    #[ORM\Column(name: 'slug', type: 'string', nullable: true, options: ['collation' => 'utf8_bin'])]
    protected $slug;

    /**
     * @var string
     * @Gedmo\Versioned
     */
    #[ORM\Column(name: 'body', type: 'text', nullable: true, options: ['collation' => 'utf8mb4_0900_ai_ci'])]
    protected $body;

    /**
     * @var string
     * @Gedmo\Versioned
     */
    #[ORM\Column(name: 'body_ref', type: 'string', nullable: true, options: ['collation' => 'utf8_bin'])]
    protected $body_ref;

    /**
     * @var int
     */
    #[ORM\Column(name: 'author_id', type: 'integer', nullable: true)]
    protected $author_id;

    /**
     * @var int
     *          Current user id which the working with data
     */
    protected $user;

    /**
     * @var Base64EncodedFile
     */
    protected $file;

    /**
     * @var int
     */
    protected $children_count;

    /**
     * @var string
     */
    #[ORM\Column(name: 'version', type: 'string', nullable: true)]
    protected $version;

    /**
     * @var string
     */
    protected $attachment;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param $uid
     *
     * @return $this
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * @return string
     */
    public function getHashId()
    {
        return $this->hash_id;
    }

    /**
     * @param $hash_id
     *
     * @return $this
     */
    public function setHashId($hash_id)
    {
        $this->hash_id = $hash_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

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
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBodyRef()
    {
        return $this->body_ref;
    }

    /**
     * @param $body_ref
     *
     * @return $this
     */
    public function setBodyRef($body_ref)
    {
        $this->body_ref = $body_ref;

        return $this;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * @param $author_id
     *
     * @return $this
     */
    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;

        return $this;
    }

    public static function getValidStatuses()
    {
        return [
            self::NODE_STATUS_PUBLISH,
        ];
    }

    public static function getValidLangCodes()
    {
        return array_keys(GeneralUtils::getLanguageCodes());
    }

    /**
     * @return string
     */
    public function getLangCode()
    {
        return $this->lang_code;
    }

    /**
     * @param $lang_code
     *
     * @return $this
     */
    public function setLangCode($lang_code)
    {
        $this->lang_code = $lang_code;

        return $this;
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
    public function setParent(Data $parent = null)
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
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * @return int
     */
    public function getNodeId()
    {
        return $this->node->getId();
    }

    /**
     * @return int|null
     */
    public function getParentId()
    {
        if ($this->parent) {
            return $this->parent->getId();
        }

        return null;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     *
     * @return Data
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Base64EncodedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param $file
     *
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public static function getValidMimeTypes()
    {
        return [
            'image/pjpeg',
            'image/jpeg',
            'image/png',
            'image/x-png',
            'image/gif',
        ];
    }

    /**
     * @return int
     */
    public function getChildrenCount()
    {
        return $this->children_count;
    }

    /**
     * @param $children_count
     *
     * @return $this
     */
    public function setChildrenCount($children_count)
    {
        $this->children_count = $children_count;

        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param $attachment
     *
     * @return $this
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttachment()
    {
        return $this->attachment;
    }
}
