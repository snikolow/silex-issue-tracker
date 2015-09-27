<?php

namespace Tracker\Component\Security\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityRepository;

class UserProvider implements UserProviderInterface {

    /** @var EntityRepository */
    private $repository;

    /**
     * @param EntityRepository $repo
     */
    public function __construct(EntityRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Fetch user from database by given e-mail address
     *
     * @param  string $email
     * @return UserInterface
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($email) {
        /* @var $user \App\Entity\User */
        $user = $this->repository->findOneByEmail($email);

        if( ! $user ) {
            throw new UsernameNotFoundException(sprintf('Account with email %s was not found!', $email));
        }
        
        if( $user->getIsAdmin() ) {
            $user->addRole('ROLE_DEVELOPER');
        }
        else {
            $user->addRole('ROLE_USER');
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     * @param  UserInterface $user
     * @return UserInterface
     * @thorws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user) {
        if ( ! $user instanceof UserInterface) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Compatibility Checks
     *
     * @param  string $class
     * @return boolean
     */
    public function supportsClass($class) {
        return $class === 'App\Entity\User';
    }

}
