<?php

namespace Tracker\Repository;

use Doctrine\ORM\EntityRepository;
use Tracker\Entity\Project;
use Tracker\Entity\User;

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
        return $this->getHomepageBaseQuery($user)
                ->addSelect('u')
                ->leftJoin('i.createdBy', 'u')
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
        return $this->getHomepageBaseQuery($user)
                ->addSelect('u')
                ->leftJoin('i.assignedTo', 'u')
                ->getQuery()
                ->getResult()
        ;
    }

    /**
     * Base query for both getCreatedIssues and getAssignedIssues
     *
     * @param  User   $user
     * @return Query
     */
    private function getHomepageBaseQuery(User $user) {
        return $this->createQueryBuilder('i')
                ->addSelect('p', 't')
                ->leftJoin('i.project', 'p')
                ->leftJoin('i.tracker', 't')
                ->where('u = :user')
                ->setParameter('user', $user)
                ->orderBy('p.id')
                ->addOrderBy('i.createdAt', 'DESC')
                ->setFirstResult(0)
                ->setMaxResults(20)
        ;
    }
}
