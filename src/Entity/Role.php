<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Brizy\Bundle\EntitiesBundle\Entity\Common\Traits\NodeableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="Brizy\Bundle\EntitiesBundle\Repository\RoleRepository")
 */
class Role
{
    use TimestampableEntity;
    use NodeableEntity;

    const DEFAULT_NAME = 'Admin';

    /**
     * The unique numeric identifier for the Role
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    protected $name = self::DEFAULT_NAME;

    /**
     * @var string
     * @ORM\Column(name="uid", type="string")
     */
    protected $uid;

    /**
     * @var bool
     * @ORM\Column(name="create_action", type="boolean")
     */
    protected $create_action;

    /**
     * @var bool
     * @ORM\Column(name="read_action", type="boolean")
     */
    protected $read_action;

    /**
     * @var bool
     * @ORM\Column(name="update_action", type="boolean")
     */
    protected $update_action;

    /**
     * @var bool
     * @ORM\Column(name="delete_action", type="boolean")
     */
    protected $delete_action;

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
     * @return bool
     */
    public function getCreateAction()
    {
        return $this->create_action;
    }

    /**
     * @param $create_action
     *
     * @return $this
     */
    public function setCreateAction($create_action)
    {
        $this->create_action = $create_action;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUpdateAction()
    {
        return $this->update_action;
    }

    /**
     * @param $update_action
     *
     * @return $this
     */
    public function setUpdateAction($update_action)
    {
        $this->update_action = $update_action;

        return $this;
    }

    /**
     * @return bool
     */
    public function getReadAction()
    {
        return $this->read_action;
    }

    /**
     * @param $read_action
     *
     * @return $this
     */
    public function setReadAction($read_action)
    {
        $this->read_action = $read_action;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDeleteAction()
    {
        return $this->delete_action;
    }

    /**
     * @param $delete_action
     *
     * @return $this
     */
    public function setDeleteAction($delete_action)
    {
        $this->delete_action = $delete_action;

        return $this;
    }
}
