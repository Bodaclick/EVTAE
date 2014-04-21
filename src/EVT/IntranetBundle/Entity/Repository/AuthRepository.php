<?php

namespace EVT\IntranetBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class AuthRepository
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class AuthRepository extends EntityRepository
{
    public function findByUserNameOrRole($userName, $role)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('a')
            ->from('EVTIntranetBundle:Auth', 'a')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->eq('a.username', ':requestUserName'),
                    $qb->expr()->eq('a.role', ':role')
                )
            )
            ->setParameter('requestUserName', $userName)
            ->setParameter('role', $role)
            ->orderBy('a.role', 'DESC')
        ;
        return $qb->getQuery()->getResult();

    }
}
