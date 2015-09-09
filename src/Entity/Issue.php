<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @Entity(repositoryClass="App\Repository\IssueRepository")
 * @Table(name="issues")
 * @HasLifecycleCallbacks
 */
class Issue {
    
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    public $id;
    
    /**
     * @Column(type="string")
     */
    public $subject;
    
    /**
     * @Column(type="text")
     */
    public $description;
    
    /**
     * @Column(type="datetime")
     */
    public $createdAt;
    
    /**
     * @Column(type="datetime")
     */
    public $updatedAt;
    
    /**
     * @Column(type="date", nullable=true)
     */
    public $dueDate;
    
    /**
     * @Column(type="integer", length=3, nullable=true)
     */
    public $doneRatio;
    
    /**
     * @var \App\Entity\Project
     * @ManyToOne(targetEntity="App\Entity\Project")
     * @JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $project;
    
    /**
     * @var \App\Entity\Tracker
     * @ManyToOne(targetEntity="App\Entity\Tracker")
     */
    public $tracker;
    
    /**
     * @var \App\Entity\Priority
     * @ManyToOne(targetEntity="App\Entity\Priority")
     */
    public $priority;
    
    /**
     * @var \App\Entity\IssueStatus
     * @ManyToOne(targetEntity="App\Entity\IssueStatus")
     */
    public $status;
    
    /**
     * @var \App\Entity\User
     * @ManyToOne(targetEntity="App\Entity\User")
     * @JoinColumn(name="assignedTo_id", referencedColumnName="id", nullable=true)
     */
    public $assignedTo;
    
    /**
     * @var \App\Entity\User
     * @ManyToOne(targetEntity="App\Entity\User")
     */
    public $createdBy;
    
    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * @PrePersist
     * @PreUpdate
     */
    public function updateTimestamp() {
        $this->updatedAt = new \DateTime();
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
    public function getSubject() {
        return $this->subject;
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
     * @return \DateTime|null
     */
    public function getDueDate() {
        return $this->dueDate;
    }

    /**
     * 
     * @return int
     */
    public function getDoneRatio() {
        return $this->doneRatio;
    }

    /**
     * 
     * @return \App\Entity\Project
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * 
     * @return \App\Entity\Tracker
     */
    public function getTracker() {
        return $this->tracker;
    }

    /**
     * 
     * @return \App\Entity\Priority
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * 
     * @return \App\Entity\IssueStatus
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * 
     * @return \App\Entity\User
     */
    public function getAssignedTo() {
        return $this->assignedTo;
    }

    /**
     * 
     * @return \App\Entity\User
     */
    public function getCreatedBy() {
        return $this->createdBy;
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
     * @param string $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
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
     * @param \DateTime $dueDate
     */
    public function setDueDate($dueDate) {
        $this->dueDate = $dueDate;
    }

    /**
     * 
     * @param int $doneRatio
     */
    public function setDoneRatio($doneRatio) {
        $this->doneRatio = $doneRatio;
    }

    /**
     * 
     * @param \App\Entity\Project $project
     */
    public function setProject(\App\Entity\Project $project) {
        $this->project = $project;
    }

    /**
     * 
     * @param \App\Entity\Tracker $tracker
     */
    public function setTracker(\App\Entity\Tracker $tracker) {
        $this->tracker = $tracker;
    }

    /**
     * 
     * @param \App\Entity\Priority $priority
     */
    public function setPriority(\App\Entity\Priority $priority) {
        $this->priority = $priority;
    }

    /**
     * 
     * @param \App\Entity\IssueStatus $status
     */
    public function setStatus(\App\Entity\IssueStatus $status) {
        $this->status = $status;
    }

    /**
     * 
     * @param \App\Entity\User $assignedTo
     */
    public function setAssignedTo(\App\Entity\User $assignedTo = null) {
        $this->assignedTo = $assignedTo;
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
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addPropertyConstraints('subject', array(
            new Assert\NotBlank(), new Assert\Length(array('min' => 3))
        ));
        $metadata->addPropertyConstraint('description', new Assert\NotBlank());
    }
}