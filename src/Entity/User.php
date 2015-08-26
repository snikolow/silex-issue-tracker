<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Entity(repositoryClass="App\Repository\UserRepository")
 * @Table(name="users")
 */
class User implements UserInterface {
    
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    public $id;
    
    /**
     * @Column(type="string", length=60)
     */
    public $email;
    
    /**
     * @Column(type="string", length=100)
     */
    public $password;
    
    /**
     * @Column(type="string", length=30)
     */
    public $name;
    
    /**
     * @Column(type="date")
     */
    public $createdAt;
    
    /**
     * @Column(type="boolean", length=1)
     */
    public $enabled = true;
    
    /**
     * @Column(type="boolean", length=1)
     */
    public $isAdmin = false;
    
    public $roles = array();
    
    public function __construct() {
        $this->createdAt = new \DateTime();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getName() {
        return $this->name;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getEnabled() {
        return $this->enabled;
    }

    public function getIsAdmin() {
        return $this->isAdmin;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    public function setRoles(array $roles) {
        $this->roles = $roles;
    }
    
    public function addRole($role) {
        array_push($this->roles, $role);
    }
        
    public function getUsername() {
        return $this->email;
    }

    public function eraseCredentials() {}
    
    public function getSalt() {}
}