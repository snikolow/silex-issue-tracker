<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository {
    
    public function getCollection() {
        return $this->createQueryBuilder('p')
                ->addSelect('u')
                ->leftJoin('p.createdBy', 'u')
                ->orderBy('p.id', 'ASC')
        ;
    }
    
}