<?php

namespace Tracker\Entity;

/**
 * @Entity(repositoryClass="Tracker\Repository\CommentRepository")
 * @Table(name="comments")
 */
class Comment {
    
    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    public $id;
    
    /**
     * @ManyToOne(targetEntity="Tracker\Entity\Issue")
     * @JoinColumn(name="issue_id", referencedColumnName="id", onDelete="CASCADE")
     */
    public $issue;
    
    /**
     * @ManyToOne(targetEntity="Tracker\Entity\User")
     * @JoinColumn(name="member_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    public $member;
    
    /**
     * @Column(type="datetime")
     */
    public $createdAt;
    
    /**
     * @Column(type="text")
     */
    public $content;
    
    public function __construct() {
        $this->createdAt = new \DateTime();
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
     * @return \Tracker\Entity\Issue
     */
    public function getIssue() {
        return $this->issue;
    }

    /**
     * 
     * @return \Tracker\Entity\User
     */
    public function getMember() {
        return $this->member;
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
     * @return string
     */
    public function getContent() {
        return $this->content;
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
     * @param \Tracker\Entity\Issue $issue
     */
    public function setIssue(\Tracker\Entity\Issue $issue) {
        $this->issue = $issue;
    }

    /**
     * 
     * @param \Tracker\Entity\User $member
     */
    public function setMember(\Tracker\Entity\User $member) {
        $this->member = $member;
    }

    /**
     * 
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }

    /**
     * 
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }


    
}