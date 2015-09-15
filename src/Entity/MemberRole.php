<?php

namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\MemberRoleRepository")
 * @Table(name="member_roles")
 */
class MemberRole {
    
    /**
     * @Id
     * @ManyToOne(targetEntity="App\Entity\User")
     * @JoinColumn(name="member_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $member;
    
    /**
     * @Id
     * @ManyToOne(targetEntity="App\Entity\Role")
     * @JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $role;
    
    /**
     * 
     * @return \App\Entity\User
     */
    public function getMember() {
        return $this->member;
    }

    /**
     * 
     * @return \App\Entity\Role
     */
    public function getRole() {
        return $this->role;
    }

    /**
     * 
     * @param \App\Entity\User $member
     */
    public function setMember(\App\Entity\User $member) {
        $this->member = $member;
    }

    /**
     * 
     * @param \App\Entity\Role $role
     */
    public function setRole(\App\Entity\Role $role) {
        $this->role = $role;
    }


    
}