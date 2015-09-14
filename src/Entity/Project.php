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
     * @JoinTable(name="project_members")
     */
    public $members;
    
    /**
     * @ManyToOne(targetEntity="App\Entity\Category")
     * @JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    public $category;
    
    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->trackers  = new ArrayCollection();
        $this->members   = new ArrayCollection();
    }
    
    /**
     * @PrePersist
     */
    public function createSlug() {
        if( ! $this->identifier ) {
            $this->identifier = Slugify::createSlug($this->title);
        }
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
    public function getDescription() {
        return $this->description;
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
     * @return bool
     */
    public function getIsPublic() {
        return $this->isPublic;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * 
     * @return \App\Entity\User
     */
    public function getCreatedBy() {
        return $this->createdBy;
    }
    
    /**
     * @return \App\Entity\Category
     */
    public function getCategory() {
        return $this->category;
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
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
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
     * @param bool $isPublic
     */
    public function setIsPublic($isPublic) {
        $this->isPublic = $isPublic;
    }

    /**
     * 
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * 
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    /**
     * 
     * @param \App\Entity\User $createdBy
     */
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

    /**
     * 
     * @param array $trackers
     */
    public function setTrackers($trackers) {
        $this->trackers = $trackers;
    }

    /**
     * 
     * @param array $members
     */
    public function setMembers($members) {
        $this->members = $members;
    }
    
    /**
     * 
     * @param \App\Entity\Category
     */
    public function setCategory(\App\Entity\Category $category = null) {
        $this->category = $category;
    }

    /**
     * 
     * @param \App\Entity\User $user
     * @return bool
     */
    public function isAlreadyMember(\App\Entity\User $user) {
        return (bool) $this->getMembers()->contains($user);
    }
        
    /**
     * 
     * @param \App\Entity\User $user
     */
    public function addMember(\App\Entity\User $user = null) {
        if( $user && ! $this->getMembers()->contains($user) ) {
            $this->getMembers()->add($user);
        }
    }
    
    /**
     * 
     * @param \App\Entity\User $user
     */
    public function removeMember(\App\Entity\User $user) {
        if( $this->getMembers()->contains($user) ) {
            $this->getMembers()->removeElement($user);
        }
    }
    
}