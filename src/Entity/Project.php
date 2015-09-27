<?php

namespace Tracker\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Tracker\Helper\String\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @Entity(repositoryClass="Tracker\Repository\ProjectRepository")
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
     * @var \Tracker\Entity\User
     * @ManyToOne(targetEntity="Tracker\Entity\User")
     * @JoinColumn(name="createdBy_id", referencedColumnName="id", nullable=true)
     */
    public $createdBy;

    /**
     * @ManyToMany(targetEntity="Tracker\Entity\Tracker")
     */
    public $trackers;

    /**
     * @OneToMany(targetEntity="Tracker\Entity\ProjectMember", mappedBy="project")
     */
    public $members;

    /**
     * @ManyToOne(targetEntity="Tracker\Entity\Category")
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
     * @return \Tracker\Entity\User
     */
    public function getCreatedBy() {
        return $this->createdBy;
    }

    /**
     * @return \Tracker\Entity\Category
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
     * @param \Tracker\Entity\User $createdBy
     */
    public function setCreatedBy(\Tracker\Entity\User $createdBy) {
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
     * @param \Tracker\Entity\Category
     */
    public function setCategory(\Tracker\Entity\Category $category = null) {
        $this->category = $category;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addPropertyConstraints('title', array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 3, 'max' => 50))
        ));

        $metadata->addPropertyConstraint('description', new Assert\NotBlank());

        $metadata->addPropertyConstraint('trackers', new Assert\Count(array('min' => 1)));
    }

}
