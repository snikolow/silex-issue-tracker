<?php

namespace Tracker\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @Entity(repositoryClass="Tracker\Repository\TrackerRepository")
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

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addPropertyConstraints('title', array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 3, 'max' => 50))
        ));
    }

}
