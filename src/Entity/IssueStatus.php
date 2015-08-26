<?php

namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\IssueStatusRepository")
 * @Table(name="issue_statuses")
 */
class IssueStatus {
    
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    public $id;
    
    /**
     * @Column(type="string")
     */
    public $title;
    
    /**
     * @Column(type="string", length=50)
     */
    public $className;
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getClassName() {
        return $this->className;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setClassName($className) {
        $this->className = $className;
    }


    
}