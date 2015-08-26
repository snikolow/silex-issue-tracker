<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository {
    
    public function getCollection() {
        return $this->createQueryBuilder('r')
                ->orderBy('r.id', 'ASC')
        ;
    }
    
}