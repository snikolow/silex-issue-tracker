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
    public function getClassName() {
        return $this->className;
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
     * @param string $className
     */
    public function setClassName($className) {
        $this->className = $className;
    }


    
}