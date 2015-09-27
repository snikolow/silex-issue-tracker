<?php

namespace Tracker\Repository;

use Doctrine\ORM\EntityRepository;
use Tracker\Entity\User;

class ProjectRepository extends EntityRepository {

    /**
     * Base query used when listing results.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCollection(User $user) {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('u', 'c')
                ->leftJoin('p.createdBy', 'u')
                ->leftJoin('p.category', 'c')
                ->orderBy('p.id', 'ASC')
        ;

        // Check if our user does not have admin rights
        // and he is regular user, select those projects
        // that he is part of.
        if( ! $user->getIsAdmin() ) {
            $qb
                    ->where(':user MEMBER OF p.members')
                    ->setParameter('user', $user);
        }

        return $qb;
    }
    
    /**
     * Find a project by given id.
     * 
     * @param int $id
     * @return \Tracker\Entity\Project|null
     */
    public function findProjectById($id) {
        return $this->createQueryBuilder('p')
                ->addSelect('c', 't', 'pm', 'r', 'm')
                ->leftJoin('p.category', 'c')
                ->leftJoin('p.trackers', 't')
                ->leftJoin('p.members', 'pm')
                ->leftJoin('pm.roles', 'r')
                ->leftJoin('pm.member', 'm')
                ->where('p.id = :id')
                ->setParameter('id', intval($id))
                ->getQuery()
                ->getOneOrNullResult()
        ;
    }

}
