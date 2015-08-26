<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PriorityRepository extends EntityRepository {
    
    public function getCollection() {
        return $this->createQueryBuilder('p')
                ->orderBy('p.id', 'ASC')
        ;
    }
    
}