<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository {
    
    public function getCollection() {
        return $this->createQueryBuilder('u')
                ->orderBy('u.id', 'ASC')
        ;
    }
    
    public function findUsersByKeyword($keyword) {
        $qb = $this->createQueryBuilder('u');
        
        return $qb
                ->select('u.id', 'u.name', 'u.email')
                ->where($qb->expr()->like('u.name', ':keyword'))
                ->setParameter('keyword', '%' . $keyword . '%')
                ->getQuery()
                ->getArrayResult();
    }
    
}