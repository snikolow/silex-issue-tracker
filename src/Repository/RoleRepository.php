<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository {
    
    /**
     * Base query used when listing results.
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCollection() {
        return $this->createQueryBuilder('r')
                ->orderBy('r.id', 'ASC')
        ;
    }
    
}