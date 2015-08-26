<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Project;
use App\Entity\User;

class IssueRepository extends EntityRepository {
    
    public function getCollectionByProject(Project $project) {
        return $this->createQueryBuilder('i')
                ->addSelect('p', 't', 'pr', 's', 'a')
                ->leftJoin('i.project', 'p')
                ->leftJoin('i.tracker', 't')
                ->leftJoin('i.priority', 'pr')
                ->leftJoin('i.status', 's')
                ->leftJoin('i.assignedTo', 'a')
                ->where('p = :project')
                ->setParameter('project', $project)
                ->orderBy('i.id', 'DESC')
        ;
    }
    
    public function getCreatedIssues(User $user) {
        return $this->createQueryBuilder('i')
                ->addSelect('u', 'p')
                ->leftJoin('i.createdBy', 'u')
                ->leftJoin('i.project', 'p')
                ->where('u = :user')
                ->setParameter('user', $user)
                ->orderBy('p.id')
                ->addOrderBy('i.createdAt', 'DESC')
                ->setFirstResult(0)
                ->setMaxResults(20)
                ->getQuery()
                ->getResult()
        ;
    }
    
    
    public function getAssignedIssues(User $user) {
        return $this->createQueryBuilder('i')
                ->addSelect('u', 'p')
                ->leftJoin('i.assignedTo', 'u')
                ->leftJoin('i.project', 'p')
                ->where('u = :user')
                ->setParameter('user', $user)
                ->orderBy('p.id')
                ->addOrderBy('i.createdAt', 'DESC')
                ->setFirstResult(0)
                ->setMaxResults(20)
                ->getQuery()
                ->getResult()
        ;
    }
}