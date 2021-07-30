<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Repository;

use Brizy\Bundle\EntitiesBundle\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\AbstractQuery;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends Common\EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserIdClientIdentifier($clientId)
    {
        $result = $this->createQueryBuilder('u')
                       ->select('u.id')
                       ->where('u.cms_api_client=:client and u.locked=false')
                       ->setParameter('client', $clientId)
                       ->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        if ($result) {
            return $result['id'];
        }
    }

    public function isProjectAllowedForUser($projectId, $userId)
    {
        // this logic is on cloud site for now.

        // return true  if the user exist
        return (bool) $this->createQueryBuilder('u')
                          ->select('u.id')
                          ->where('u.id=:user_id and u.locked=false')
                          ->setParameter('user_id', $userId)
                          ->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
