<?php

namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\TrackerRepository")
 * @Table(name="trackers")
 */
class Tracker {
    
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
    
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    
}