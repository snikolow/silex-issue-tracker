<?php

namespace Tracker\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {
    
    /**
     * Base query used when listing results.
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCollection() {
        return $this->createQueryBuilder('c')
                ->orderBy('c.id', 'ASC')
        ;
    }
    
}