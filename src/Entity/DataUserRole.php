<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Brizy\Bundle\EntitiesBundle\Repository\DataUserRoleRepository;
use Brizy\Bundle\EntitiesBundle\Utils\Random;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'data_user_role', uniqueConstraints: [new UniqueConstraint(columns: ['data_id', 'user_id', 'role_uid'])])]
#[ORM\Entity(repositoryClass: DataUserRoleRepository::class)]
class DataUserRole
{
    use TimestampableEntity;

    final public const STATUS_APPROVED = 1;

    final public const STATUS_PENDING = 2;

    /**
     * The unique numeric identifier for the Project
     */
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private readonly int $id;

    /**
     * @var Data
     */
    #[Assert\NotBlank]
    #[ORM\ManyToOne(targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\Data::class)]
    #[ORM\JoinColumn(name: 'data_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    protected $data;

    /**
     * @var User
     */
    #[ORM\ManyToOne(targetEntity: \Brizy\Bundle\EntitiesBundle\Entity\User::class, inversedBy: 'dataUserRoles')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected $user;

    /**
     * @var string
     */
    #[Assert\NotBlank]
    #[ORM\Column(name: 'role_uid', type: 'string')]
    protected $role_uid;

    /**
     * @var int
     */
    #[Assert\Choice(callback: 'getValidStatuses', strict: true)]
    #[ORM\Column(name: 'status', type: 'integer', nullable: true)]
    protected $status = self::STATUS_PENDING;

    /**
     * @var string
     */
    #[ORM\Column(name: 'token', type: 'string', nullable: true)]
    protected $token;

    /**
     * @var int
     */
    #[ORM\Column(name: 'timestamp', type: 'integer', nullable: true)]
    protected $timestamp;

    public function __construct()
    {
        $this->token = Random::generateAccessToken();
        $this->timestamp = time();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoleUid()
    {
        return $this->role_uid;
    }

    /**
     * @param string $role_uid
     *
     * @return $this
     */
    public function setRoleUid($role_uid)
    {
        $this->role_uid = $role_uid;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public static function getValidStatuses()
    {
        return [
            self::STATUS_APPROVED,
            self::STATUS_PENDING,
        ];
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     *
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
