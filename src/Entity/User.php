<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Brizy\Bundle\EntitiesBundle\Entity\Common\Traits\MetafieldableEntity;
use Brizy\Bundle\EntitiesBundle\Entity\Common\Traits\NodeableEntity;
use Brizy\Bundle\EntitiesBundle\Utils\Random;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;

#[ORM\Table(name: 'user', uniqueConstraints: [new UniqueConstraint(columns: ['application_id', 'user_remote_id'])])]
#[ORM\Entity(repositoryClass: \Brizy\Bundle\EntitiesBundle\Repository\UserRepository::class)]
class User implements UserInterface
{
    use TimestampableEntity;
    use NodeableEntity;
    use MetafieldableEntity;

    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var Application
     */
    #[ORM\ManyToOne(targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\Application::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'application_id', referencedColumnName: 'id', nullable: true)]
    protected $application;

    /**
     * OAuth Client to access the CmsApplication api
     *
     * @var Client
     */
    #[ORM\ManyToOne(targetEntity: \Trikoder\Bundle\OAuth2Bundle\Model\Client::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'cms_api_client_id', referencedColumnName: 'identifier', nullable: true)]
    protected $cms_api_client;

    /**
     * @var int
     */
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 2_147_483_647)]
    #[ORM\Column(name: 'user_remote_id', type: 'integer')]
    protected $user_remote_id;

    /**
     * @var string
     */
    #[ORM\Column(name: 'token', type: 'string')]
    protected $token;

    /**
     * @var string
     */
    #[ORM\Column(name: 'fingerprint', type: 'string', nullable: true)]
    protected $fingerprint;

    /**
     * @var \DateTime
     */
    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    protected $deleted_at;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'outdated', type: 'boolean')]
    protected $outdated = false;

    /**
     * @var array
     */
    #[ORM\Column(name: 'products', type: 'json', nullable: true)]
    protected $products;

    #[ORM\OneToMany(targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\DataUserRole::class, mappedBy: 'user', fetch: 'EXTRA_LAZY', cascade: ['remove'])]
    protected $dataUserRoles;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'locked', type: 'boolean', options: ['default' => false])]
    protected $locked = false;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'approved', type: 'boolean', options: ['default' => false])]
    protected $approved = false;

    /**
     * @var string
     */
    #[ORM\Column(name: 'status', type: 'string', nullable: true)]
    protected $status;

    public function __construct()
    {
        $this->generateToken();
        $this->dataUserRoles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @return $this
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * @return Client
     */
    public function getCmsApiClient(): ?Client
    {
        return $this->cms_api_client;
    }

    public function setCmsApiClient(?Client $cms_api_client): User
    {
        $this->cms_api_client = $cms_api_client;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserRemoteId()
    {
        return $this->user_remote_id;
    }

    /**
     * @param int $user_remote_id
     *
     * @return $this
     */
    public function setUserRemoteId($user_remote_id)
    {
        $this->user_remote_id = $user_remote_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function generateToken()
    {
        $this->token = Random::generateAccessToken();
    }

    /**
     * @return string
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }

    /**
     * @param $fingerprint
     *
     * @return $this
     */
    public function setFingerprint($fingerprint)
    {
        $this->fingerprint = $fingerprint;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * @return $this
     */
    public function setDeletedAt(\DateTime $deleted_at)
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    /**
     * @return string
     */
    public function getOutdated()
    {
        return $this->outdated;
    }

    /**
     * @param $outdated
     *
     * @return $this
     */
    public function setOutdated($outdated)
    {
        $this->outdated = $outdated;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return $this
     */
    public function setProducts(array $products = null)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getDataUserRoles()
    {
        return $this->dataUserRoles;
    }

    /**
     * @return $this
     */
    public function setDataUserRoles(ArrayCollection $dataUserRoles)
    {
        $this->dataUserRoles = $dataUserRoles;

        return $this;
    }

    /**
     * @return bool
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * @param $locked
     *
     * @return $this
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * @return bool
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * @param $approved
     *
     * @return $this
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

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
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[] The user roles
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword()
    {
        return null;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return null;
    }

    public function getUserIdentifier()
    {
        return $this->getId();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        return null;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}
