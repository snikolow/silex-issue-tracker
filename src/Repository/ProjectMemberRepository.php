<?php

namespace Tracker\Repository;

use Doctrine\ORM\EntityRepository;

use Tracker\Entity\Project;
use Tracker\Entity\User;

class ProjectMemberRepository extends EntityRepository {
    
    /**
     * 
     * @param Project $project
     * @param User $member
     * @return \Tracker\Entity\ProjectMember|null
     */
    public function isAlreadyAMember(Project $project, User $member) {
        return $this->createQueryBuilder('pm')
                ->where('pm.project = :project')
                ->andWhere('pm.member = :member')
                ->setParameters(
                        array(
                            'project' => $project,
                            'member'  => $member
                        )
                )
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult()
        ;
    }
    
}