<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class IssueStatusRepository extends EntityRepository {
    
    public function getCollection() {
        return $this->createQueryBuilder('s')
                ->orderBy('s.id', 'ASC')
        ;
    }
    
}