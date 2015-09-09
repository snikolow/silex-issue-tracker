<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class IssueStatusRepository extends EntityRepository {
    
    /**
     * Base query used when listing results.
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCollection() {
        return $this->createQueryBuilder('s')
                ->orderBy('s.id', 'ASC')
        ;
    }
    
}