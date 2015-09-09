<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PriorityRepository extends EntityRepository {
    
    /**
     * Base query used when listing results.
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCollection() {
        return $this->createQueryBuilder('p')
                ->orderBy('p.id', 'ASC')
        ;
    }
    
}