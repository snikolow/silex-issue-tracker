<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Helper\String\Slugify;

/**
 * @Entity(repositoryClass="App\Repository\ProjectRepository")
 * @Table(name="projects")
 * @HasLifecycleCallbacks
 */
class Project {
    
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
     * @Column(type="text")
     */
    public $description;
    
    /**
     * @Column(type="string", length=120)
     */
    public $identifier;
    
    /**
     * @Column(type="boolean", length=1)
     */
    public $isPublic = true;
    
    /**
     * @Column(type="date")
     */
    public $createdAt;
    
    /**
     * @Column(type="date")
     */
    public $updatedAt;
    
    /**
     * @var \App\Entity\User
     * @ManyToOne(targetEntity="App\Entity\User")
     * @JoinColumn(name="createdBy_id", referencedColumnName="id", nullable=true)
     */
    public $createdBy;
    
    /**
     * @ManyToMany(targetEntity="App\Entity\Tracker")
     */
    public $trackers;
    
    /**
     * @ManyToMany(targetEntity="App\Entity\User")
     */
    public $members;
    
    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->trackers  = new ArrayCollection();
    }
    
    /**
     * @PrePersist
     */
    public function createSlug() {
        if( ! $this->identifier ) {
            $this->identifier = Slugify::createSlug($this->title);
        }
    }
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function getIsPublic() {
        return $this->isPublic;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function getCreatedBy() {
        return $this->createdBy;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    public function setIsPublic($isPublic) {
        $this->isPublic = $isPublic;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function setCreatedBy(\App\Entity\User $createdBy) {
        $this->createdBy = $createdBy;
    }

    /**
     * 
     * @return ArrayCollection
     */
    public function getTrackers() {
        return $this->trackers;
    }

    /**
     * 
     * @return ArrayCollection
     */
    public function getMembers() {
        return $this->members;
    }

    public function setTrackers($trackers) {
        $this->trackers = $trackers;
    }

    public function setMembers($members) {
        $this->members = $members;
    }
    
}