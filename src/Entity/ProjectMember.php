<?php

namespace Tracker\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Tracker\Repository\ProjectMemberRepository")
 * @Table(name="project_members")
 */
class ProjectMember {
    
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    public $id;
    
    /**
     * @var \Tracker\Entity\User
     * @ManyToOne(targetEntity="Tracker\Entity\User")
     * @JoinColumn(name="member_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $member;
    
    /**
     * @var \Tracker\Entity\Project
     * @ManyToOne(targetEntity="Tracker\Entity\Project", inversedBy="members")
     * @JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $project;
    
    /**
     * @ManyToMany(targetEntity="Tracker\Entity\Role")
     * @JoinTable(name="project_members_roles")
     */
    public $roles;
    
    public function __construct() {
        $this->roles = new ArrayCollection();
    }
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return \Tracker\Entity\User
     */
    public function getMember() {
        return $this->member;
    }

    /**
     * 
     * @return \Tracker\Entity\Project
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @param \Tracker\Entity\User $member
     */
    public function setMember(\Tracker\Entity\User $member) {
        $this->member = $member;
    }

    /**
     * 
     * @param \Tracker\Entity\Project $project
     */
    public function setProject(\Tracker\Entity\Project $project) {
        $this->project = $project;
    }

    /**
     * 
     * @return ArrayCollection
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * 
     * @param array $roles
     */
    public function setRoles($roles) {
        $this->roles = $roles;
    }

    /**
     * 
     * @return string
     */
    public function getMemberRolesToString() {
        $roles = array();
        
        foreach($this->getRoles()->toArray() as $role) {
            $roles[] = $role->getTitle();
        }
        
        return implode(',', $roles);
    }
    
}