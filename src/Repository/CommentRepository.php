<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Issue;

class CommentRepository extends EntityRepository {
    
    /**
     * Get all comments related to specific issue.
     * 
     * @param Issue $entity
     */
    public function getIssueComments(Issue $entity) {
        return $this->createQueryBuilder('c')
                ->addSelect('m', 'i', 'p')
                ->leftJoin('c.member', 'm')
                ->leftJoin('c.issue', 'i')
                ->leftJoin('i.project', 'p')
                ->where('c.issue = :issue')
                ->setParameter('issue', $entity)
                ->orderBy('c.createdAt', 'DESC')
                ->getQuery()
                ->getResult()
        ;
    }
    
}