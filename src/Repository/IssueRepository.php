<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Project;
use App\Entity\User;

class IssueRepository extends EntityRepository {
    
    /**
     * Get all issues for specific project.
     * 
     * @param Project $project
     * @return array
     */
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
    
    /**
     * Get the last 20 issues created by specific user.
     * 
     * @param User $user
     * @return array
     */
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
    
    /**
     * Get the last 20 issues that are assigned to specific user.
     * 
     * @param User $user
     * @return array
     */
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