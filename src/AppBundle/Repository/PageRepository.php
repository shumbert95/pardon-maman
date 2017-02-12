<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 */
class PageRepository extends \Doctrine\ORM\EntityRepository
{
    public function findOrderedByPosition()
    {
        $qb = $this->createQueryBuilder('p');

        $qb->where('p.status = 1')
            ->orderBy('p.position', 'ASC')
            ->distinct();

        return $qb->getQuery()->getResult();
    }
}
