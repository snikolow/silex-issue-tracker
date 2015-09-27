<?php

namespace Tracker\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @Entity(repositoryClass="Tracker\Repository\IssueStatusRepository")
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

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('title', array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 3))
        ));

        $metadata->addPropertyConstraint('className', new Assert\NotBlank());
    }

}
