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
    
    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * 
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * 
     * @return ArrayCollection
     */
    public function getPermissions() {
        return $this->permissions;
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
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * 
     * @param string $identifier
     */
    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    /**
     * 
     * @param array $permissions
     */
    public function setPermissions($permissions) {
        $this->permissions = $permissions;
    }


    
}