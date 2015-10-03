<?php

namespace Tracker\Component\Security\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Tracker\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter extends AbstractVoter {

    const VIEW = 'view';
    const EDIT = 'edit';

    protected function getSupportedAttributes() {
        return array(self::VIEW, self::EDIT);
    }

    protected function getSupportedClasses() {
        return array('Tracker\Entity\Project');
    }

    /**
     *
     * @param string $attribute
     * @param \Tracker\Entity\Project $project
     * @param \Tracker\Entity\User $user
     * @return boolean
     * @throws \LogicException
     */
    protected function isGranted($attribute, $project, $user = null) {
        // make sure there is a user object (i.e. that the user is logged in)
        if( ! $user instanceof UserInterface ) {
            return false;
        }

        // double-check that the User object is the expected entity.
        // It always will be, unless there is some misconfiguration of the
        // security system.
        if( ! $user instanceof User ) {
            throw new \LogicException('The user is somehow not our User class!');
        }

        // If the current user have administrator rights, we should return true
        if( $user->getIsAdmin() ) {
            return true;
        }

        switch ($attribute) {
            case self::VIEW:
                foreach($project->getMembers() as $item) {
                    if( $item->getMember()->getId() === $user->getId() ) {
                        return true;
                    }
                }
                
                break;
            case self::EDIT:

                break;
        }

        return false;
    }

}
