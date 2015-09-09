<?php

namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\CommentRepository")
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
     * @ManyToOne(targetEntity="App\Entity\Issue")
     */
    public $issue;
    
    /**
     * @ManyToOne(targetEntity="App\Entity\User")
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
     * @return \App\Entity\Issue
     */
    public function getIssue() {
        return $this->issue;
    }

    /**
     * 
     * @return \App\Entity\User
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
     * @param \App\Entity\Issue $issue
     */
    public function setIssue(\App\Entity\Issue $issue) {
        $this->issue = $issue;
    }

    /**
     * 
     * @param \App\Entity\User $member
     */
    public function setMember(\App\Entity\User $member) {
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