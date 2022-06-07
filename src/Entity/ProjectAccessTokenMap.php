<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Entity;

use Brizy\Bundle\EntitiesBundle\Repository\ProjectAccessTokenMapRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Trikoder\Bundle\OAuth2Bundle\Model\AccessToken;

#[ORM\Table(name: 'project_access_token')]
#[ORM\Entity(repositoryClass: ProjectAccessTokenMapRepository::class)]
class ProjectAccessTokenMap
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    #[ORM\ManyToOne(targetEntity: Data::class)]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    protected Data $project;

    private ?AccessToken $token = null;

    protected $accessToken = null;

    public function __construct(Data $project = null, ?AccessToken $token = null)
    {
        $this->project = $project;
        $this->token = $token;
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken(): ?AccessToken
    {
        return $this->accessToken;
    }

    public function setAccessToken(AccessToken $accessToken): ProjectAccessTokenMap
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return Data
     */
    public function getProject(): ?Data
    {
        return $this->project;
    }

    public function setProject(Data $project): ProjectAccessTokenMap
    {
        $this->project = $project;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id)
    {
        $this->id = $id;

        return $this;
    }
}
