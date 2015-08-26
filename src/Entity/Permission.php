<?php

namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\PermissionRepository")
 * @Table(name="permissions")
 */
class Permission {
    
    const VIEW_PROJECT = 'view.project';
    
    const CREATE_ISSUE = 'create.issue';
    const EDIT_ISSUE = 'edit.issue';
    const VIEW_ISSUE = 'view.issue';
    const DELETE_ISSUE = 'delete.issue';
    
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
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getIdentifier() {
        return $this->identifier;
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

    public static function getChoices() {
        return array(
            self::VIEW_PROJECT => 'View project',
            
            self::CREATE_ISSUE => 'Create issue',
            self::EDIT_ISSUE => 'Edit issue',
            self::VIEW_ISSUE => 'View issue',
            self::DELETE_ISSUE => 'Delete issue',
        );
    }
    
}