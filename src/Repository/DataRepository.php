<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Repository;

use Brizy\Bundle\EntitiesBundle\Entity\Data;
use Brizy\Bundle\EntitiesBundle\Entity\DataUserRole;
use Brizy\Bundle\EntitiesBundle\Entity\Node;
use Brizy\Bundle\EntitiesBundle\Entity\ProjectAccessTokenMap;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;
use Trikoder\Bundle\OAuth2Bundle\Model\AccessToken;

/**
 * @method Data|null find($id, $lockMode = null, $lockVersion = null)
 * @method Data|null findOneBy(array $criteria, array $orderBy = null)
 * @method Data[]    findAll()
 * @method Data[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataRepository extends EntityRepository
{
    /**
     * DataRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Data::class);
    }

    /**
     * @param $accessToken
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getProjectByAccessTokenQueryBuilder($accessToken)
    {
        $qb = $this->createQueryBuilder('p')
            ->join(
                ProjectAccessTokenMap::class,
                'm',
                'WITH',
                'p.id=m.project and m.accessToken=:token'
            )
            ->join(
                AccessToken::class,
                't',
                'WITH',
                't.identifier=m.accessToken  AND t.revoked=0 AND t.expiry>:currentData'
            )
            ->setParameters(
                [
                    'token' => $accessToken,
                    'currentData' => new \DateTime(),
                ]
            );

        return $qb;
    }

    /**
     * @param $accessToken
     *
     * @return int|mixed|string|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getProjectIdByAccessToken($accessToken)
    {
        $qb = $this->getProjectByAccessTokenQueryBuilder($accessToken);
        $qb->select('p.id');

        return $qb->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * @param $accessToken
     *
     * @return int|mixed|string
     */
    public function getProjectByAccessToken($accessToken)
    {
        $qb = $this->getProjectByAccessTokenQueryBuilder($accessToken);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Return all projects where the user id has some role set.
     *
     * @param $userId
     */
    public function getUserRelatedProjectIds($userId): array
    {
        $enabledItBack = false;
        if ($this->_em->getFilters()->isEnabled('user_scope')) {
            $this->_em->getFilters()->disable('user_scope');
            $enabledItBack = true;
        }

        $result = $this->createQueryBuilder('p')
            ->select('p.id')
            ->join(
                Node::class,
                'n',
                'WITH',
                'p.node=n.id and n.slug=\'project\''
            )
            ->join(
                DataUserRole::class,
                'r',
                'WITH',
                'r.data = p.parent and r.user=:user_id'
            )
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getScalarResult();

        if ($enabledItBack) {
            $this->_em->getFilters()->enable('user_scope');
        }

        return array_map(fn ($value) => (int) $value, array_column($result, 'id'));
    }
}
