<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class TrackerRepository extends EntityRepository {
    
    public function getCollection() {
        return $this->createQueryBuilder('t')
                ->orderBy('t.id', 'ASC')
        ;
    }
    
}