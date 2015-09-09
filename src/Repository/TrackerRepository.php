<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class TrackerRepository extends EntityRepository {
    
    /**
     * Base query used when listing results.
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCollection() {
        return $this->createQueryBuilder('t')
                ->orderBy('t.id', 'ASC')
        ;
    }
    
}