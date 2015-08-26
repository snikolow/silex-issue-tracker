<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="App\Repository\RoleRepository")
 * @Table(name="roles")
 */
class Role {
    
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    public $id;
    
    /**
     * @Column(type="string", length=100)
     */
    public $title;
    
    /**
     * @Column(type="string", length=50)
     */
    public $identifier;
    
    /**
     * @ManyToMany(targetEntity="App\Entity\Permission")
     */
    public $permissions;
    
    public function __construct() {
        $this->permissions = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function getPermissions() {
        return $this->permissions;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    public function setPermissions($permissions) {
        $this->permissions = $permissions;
    }


    
}