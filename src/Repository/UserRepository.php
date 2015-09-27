<?php

namespace Tracker\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {

    /**
     * Base query used when listing results.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCollection() {
        return $this->createQueryBuilder('u')
                ->orderBy('u.id', 'ASC')
        ;
    }

    /**
     * Get a resultset of users filtered by name.
     * Used when adding member to a project, for
     * autocomplete plugin.
     *
     * @param string $keyword
     * @return array
     */
    public function findUsersByKeyword($keyword) {
        $qb = $this->createQueryBuilder('u');

        return $qb
                ->select('u.id', 'u.name', 'u.email')
                ->where($qb->expr()->like('u.name', ':keyword'))
                ->andWhere('u.enabled = 1')
                ->setParameter('keyword', '%' . $keyword . '%')
                ->getQuery()
                ->getArrayResult();
    }

}
