<?php

namespace Tracker\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @Entity(repositoryClass="Tracker\Repository\IssueRepository")
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
     * @var \Tracker\Entity\Project
     * @ManyToOne(targetEntity="Tracker\Entity\Project")
     * @JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $project;
    
    /**
     * @var \Tracker\Entity\Tracker
     * @ManyToOne(targetEntity="Tracker\Entity\Tracker")
     */
    public $tracker;
    
    /**
     * @var \Tracker\Entity\Priority
     * @ManyToOne(targetEntity="Tracker\Entity\Priority")
     */
    public $priority;
    
    /**
     * @var \Tracker\Entity\IssueStatus
     * @ManyToOne(targetEntity="Tracker\Entity\IssueStatus")
     */
    public $status;
    
    /**
     * @var \Tracker\Entity\User
     * @ManyToOne(targetEntity="Tracker\Entity\User")
     * @JoinColumn(name="assignedTo_id", referencedColumnName="id", nullable=true)
     */
    public $assignedTo;
    
    /**
     * @var \Tracker\Entity\User
     * @ManyToOne(targetEntity="Tracker\Entity\User")
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
     * @return \Tracker\Entity\Project
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * 
     * @return \Tracker\Entity\Tracker
     */
    public function getTracker() {
        return $this->tracker;
    }

    /**
     * 
     * @return \Tracker\Entity\Priority
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * 
     * @return \Tracker\Entity\IssueStatus
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * 
     * @return \Tracker\Entity\User
     */
    public function getAssignedTo() {
        return $this->assignedTo;
    }

    /**
     * 
     * @return \Tracker\Entity\User
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
     * @param \Tracker\Entity\Project $project
     */
    public function setProject(\Tracker\Entity\Project $project) {
        $this->project = $project;
    }

    /**
     * 
     * @param \Tracker\Entity\Tracker $tracker
     */
    public function setTracker(\Tracker\Entity\Tracker $tracker) {
        $this->tracker = $tracker;
    }

    /**
     * 
     * @param \Tracker\Entity\Priority $priority
     */
    public function setPriority(\Tracker\Entity\Priority $priority) {
        $this->priority = $priority;
    }

    /**
     * 
     * @param \Tracker\Entity\IssueStatus $status
     */
    public function setStatus(\Tracker\Entity\IssueStatus $status) {
        $this->status = $status;
    }

    /**
     * 
     * @param \Tracker\Entity\User $assignedTo
     */
    public function setAssignedTo(\Tracker\Entity\User $assignedTo = null) {
        $this->assignedTo = $assignedTo;
    }

    /**
     * 
     * @param \Tracker\Entity\User $createdBy
     */
    public function setCreatedBy(\Tracker\Entity\User $createdBy) {
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