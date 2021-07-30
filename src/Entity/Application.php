<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Brizy\Bundle\EntitiesBundle\Utils\Random;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @ORM\Table(name="application")
 * @ORM\Entity(repositoryClass="Brizy\Bundle\EntitiesBundle\Repository\ApplicationRepository")
 */
class Application
{
    use TimestampableEntity;

    const SCOPE_AUTH = 'user_auth';
    const SCOPE_SUPER_ADMIN = 'super_admin';

    /**
     * The unique numeric identifier for the Node
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
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string")
     */
    protected $api_key;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string")
     */
    protected $secret;

    /**
     * @var string
     *
     * @ORM\Column(name="client_id", type="string")
     */
    protected $client_id;

    /**
     * @var string
     *
     * @ORM\Column(name="scope", type="string")
     */
    protected $scope;

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
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * @param $api_key
     *
     * @return $this
     */
    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;

        return $this;
    }

    /**
     * @return $this
     */
    public function generateApiKey()
    {
        if (!$this->client_id || !$this->secret) {
            throw new BadRequestHttpException("client_id and secret can't be null");
        }

        $this->api_key = Random::generateAccessToken();

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \Exception
     */
    public function generateClientId()
    {
        $this->client_id = Random::generateToken();

        return $this;
    }

    /**
     * @return $this
     *
     * @throws \Exception
     */
    public function generateSecret()
    {
        if (!$this->client_id) {
            throw new BadRequestHttpException("client_id can't be null");
        }

        $this->secret = Random::generateToken();

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param $secret
     *
     * @return $this
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param $client_id
     *
     * @return $this
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     *
     * @return $this
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    public static function getValidScopes()
    {
        return [
            'User Authorization' => self::SCOPE_AUTH,
            'Super Admin' => self::SCOPE_SUPER_ADMIN,
        ];
    }
}
