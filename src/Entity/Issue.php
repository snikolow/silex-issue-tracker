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
    
    public function getId() {
        return $this->id;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function getDueDate() {
        return $this->dueDate;
    }

    public function getDoneRatio() {
        return $this->doneRatio;
    }

    public function getProject() {
        return $this->project;
    }

    public function getTracker() {
        return $this->tracker;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getAssignedTo() {
        return $this->assignedTo;
    }

    public function getCreatedBy() {
        return $this->createdBy;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt($updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function setDueDate($dueDate) {
        $this->dueDate = $dueDate;
    }

    public function setDoneRatio($doneRatio) {
        $this->doneRatio = $doneRatio;
    }

    public function setProject(\App\Entity\Project $project) {
        $this->project = $project;
    }

    public function setTracker(\App\Entity\Tracker $tracker) {
        $this->tracker = $tracker;
    }

    public function setPriority(\App\Entity\Priority $priority) {
        $this->priority = $priority;
    }

    public function setStatus(\App\Entity\IssueStatus $status) {
        $this->status = $status;
    }

    public function setAssignedTo(\App\Entity\User $assignedTo = null) {
        $this->assignedTo = $assignedTo;
    }

    public function setCreatedBy(\App\Entity\User $createdBy) {
        $this->createdBy = $createdBy;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addPropertyConstraint('subject', new Assert\NotBlank());
        $metadata->addPropertyConstraint('subject', new Assert\Length(array('min' => 3)));
    }
}