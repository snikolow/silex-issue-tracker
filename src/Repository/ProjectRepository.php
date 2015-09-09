<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\User;

class ProjectRepository extends EntityRepository {
    
    /**
     * Base query used when listing results.
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCollection(User $user) {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('u')
                ->leftJoin('p.createdBy', 'u')
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
    
}