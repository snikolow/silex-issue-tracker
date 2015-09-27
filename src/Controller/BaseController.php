<?php

namespace Tracker\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\FormInterface;
use Tracker\Application;

class BaseController {

    /**
     * @var Application
     */
    protected $application;

    public function __construct(Application $app) {
        $this->application = $app;
    }

    /**
     * Check for existing service or factory definition by given id.
     * If service is found, return it, otherwise return null.
     *
     * @param  string $id
     * @return mixed|null
     */
    public function get($id) {
        if( isset($this->application[ $id ]) ) {
            return $this->application[ $id ];
        }

        return null;
    }

    /**
     * Get entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getManager() {
        return $this->get('orm.em');
    }

    /**
     * Return a repository instance based on the class name.
     *
     * @param string $class
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($class) {
        if( strpos($class, 'Tracker\Entity') !== false ) {
            $class = str_replace('Tracker\Entity', '', $class);
        }

        $namespace = sprintf('Tracker\\Entity\\%s', $class);

        return $this->getManager()->getRepository($namespace);
    }

    /**
     * Shortcut for persisting an entity.
     * This should be executed last, or at least not abused
     * since flush is also being executed.
     *
     * @param mixed $entity
     * @return \Tracker\Controller\BaseController
     */
    public function persistAndFlush($entity) {
        $this->getManager()->persist($entity);
        $this->getManager()->flush();

        return $this;
    }

    /**
     * See @persistAndFlush
     *
     * @param mixed $entity
     * @return \Tracker\Controller\BaseController
     */
    public function removeAndFlush($entity) {
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        return $this;
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url    The URL to redirect to
     * @param int    $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302) {
        return new RedirectResponse($url, $status);
    }

    /**
     * Add a flash message to session.
     *
     * @param string $type
     * @param string $message
     */
    public function addFlash($type, $message) {
        $this->get('session')->getFlashBag()->add($type, $message);

        return $this;
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param string $route      The name of the route
     * @param array  $parameters An array of parameters
     * @param int    $status     The status code to use for the Response
     *
     * @return RedirectResponse
     */
    public function redirectToRoute($route, array $parameters = array(), $status = 302) {
        return $this->redirect($this->url($route, $parameters), $status);
    }

    /**
     * Define render method in base controller
     *
     * @param string $view
     * @param array $parameters
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    protected function render($view, array $parameters = array(), Response $response = null) {
        return $this->application->render($view, $parameters, $response);
    }

    /**
     * Translates the given message.
     *
     * @param string $id         The message id
     * @param array  $parameters An array of parameters for the message
     * @param string $domain     The domain for the message
     * @param string $locale     The locale
     *
     * @return string The translated string
     */
    public function trans($id, array $parameters = array(), $domain = 'messages', $locale = null) {
        return $this->application->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @param string $id         The message id
     * @param int    $number     The number to use to find the indice of the message
     * @param array  $parameters An array of parameters for the message
     * @param string $domain     The domain for the message
     * @param string $locale     The locale
     *
     * @return string The translated string
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = 'messages', $locale = null) {
        return $this->application->transChoice($id, $number, $parameters, $domain, $locale);
    }

    /**
     * Generates a path from the given parameters.
     *
     * @param string $route      The name of the route
     * @param mixed  $parameters An array of parameters
     *
     * @return string The generated path
     */
    public function path($route, $parameters = array()) {
        return $this->application->path($route, $parameters);
    }

    /**
     * Generates an absolute URL from the given parameters.
     *
     * @param string $route      The name of the route
     * @param mixed  $parameters An array of parameters
     *
     * @return string The generated URL
     */
    public function url($route, $parameters = array()) {
        return $this->application->url($route, $parameters);
    }

    /**
     * Encodes the raw password.
     *
     * @param UserInterface $user     A UserInterface instance
     * @param string        $password The password to encode
     *
     * @return string The encoded password
     * @throws \RuntimeException when no password encoder could be found for the user
     */
    public function encodePassword(UserInterface $user, $password) {
        return $this->get('security.encoder_factory')->getEncoder($user)->encodePassword($password, $user->getSalt());
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @param mixed $attributes
     * @param mixed $object
     *
     * @return bool
     */
    public function isGranted($attributes, $object = null) {
        return $this->get('security.authorization_checker')->isGranted($attributes, $object);
    }

    /**
     * Throws an exception unless the attributes are granted against the current authentication token and optionally
     * supplied object.
     *
     * @param mixed  $attributes The attributes
     * @param mixed  $object     The object
     * @param string $message    The message passed to the exception
     *
     * @throws HttpException
     */
    public function denyAccessUnlessGranted($attributes, $object = null, $message = 'Access Denied.')
    {
        if( ! $this->isGranted($attributes, $object) ) {
            $this->application->abort(403, $message);
        }
    }

    /**
     * Return an instance of currently logged user (if present).
     *
     * @return \Tracker\Entity\User|null
     */
    public function getUser() {
        return $this->get('user');
    }

    /**
     *
     * @param FormInterface $form
     * @return array
     */
    public function getFormErrors(FormInterface $form) {
        $errors = array();

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $child) {
            if ($err = $this->getFormErrors($child)) {
                $errors[$key] = $err;
            }
        }

        return $errors;
    }

}
