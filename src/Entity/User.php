<?php

namespace Tracker\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @Entity(repositoryClass="Tracker\Repository\UserRepository")
 * @Table(name="users")
 */
class User implements UserInterface {

    /**
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    public $id;

    /**
     * @Column(type="string", length=60)
     */
    public $email;

    /**
     * @Column(type="string", length=100)
     */
    public $password;

    /**
     * @Column(type="string", length=30)
     */
    public $name;

    /**
     * @Column(type="date")
     */
    public $createdAt;

    /**
     * @Column(type="boolean", length=1)
     */
    public $enabled = true;

    /**
     * @Column(type="boolean", length=1)
     */
    public $isAdmin = false;

    /**
     * This is unmapped property and we store either
     * ROLE_ADMIN or ROLE_USER based on $isAdmin property,
     * since it's necessary because we implement UserInterface
     *
     * @var array
     */
    public $roles = array();

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
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     *
     * @return string
     */
    public function getName() {
        return $this->name;
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
     * @return bool
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     *
     * @return bool
     */
    public function getIsAdmin() {
        return $this->isAdmin;
    }

    /**
     *
     * @return array
     */
    public function getRoles() {
        return $this->roles;
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
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
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
     * @param bool $enabled
     */
    public function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    /**
     *
     * @param bool $isAdmin
     */
    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    /**
     *
     * @param array $roles
     */
    public function setRoles(array $roles) {
        $this->roles = $roles;
    }

    /**
     *
     * @param string $role
     */
    public function addRole($role) {
        array_push($this->roles, $role);
    }

    /**
     *
     * @return string
     */
    public function getUsername() {
        return $this->email;
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials() {}

    /**
     * {@inheritDoc}
     */
    public function getSalt() {}

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addPropertyConstraints('email', array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 3, 'max' => 50)),
            new Assert\Email()
        ));

        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraints('password', array(
            new Assert\NotBlank(),
            new Assert\Length(array('min' => 6))
        ));
    }
}
