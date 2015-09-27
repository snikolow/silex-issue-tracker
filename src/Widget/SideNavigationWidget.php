<?php

namespace Tracker\Widget;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SideNavigationWidget extends AbstractWidget {
    
    /** @var AuthorizationCheckerInterface */
    private $authChecker = null;
    
    /** @var Request */
    private $request = null;
    
    /**
     * 
     * @param AuthorizationCheckerInterface $checker
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $checker) {
        $this->authChecker = $checker;
    }
    
    /**
     * 
     * @param RequestStack $requestStack
     */
    public function setRequest(RequestStack $requestStack) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getContent() {
        /**
         * Filter navigation items based on user roles before we pass them to our template.
         * It's better to do this kind of checks here, rather than our template.
         */
        $items = array_filter($this->getNavigationLinks(), function($item) {
            
            // If no "roles" option is provided, we simply return the item.
            if( ! isset($item['roles']) ) {
                return $item;
            }
            
            // If "roles" is provided then we check it against currently logged user's roles.
            if( isset($item['roles']) && $this->authChecker->isGranted($item['roles']) ) {
                return $item;
            }
            
        });
        
        return $this->render('widget/sideNavigation.twig',
            array(
                'collection' => $items
            )
        );
    }

    /**
     * Create simple array with sidebar navigation items.
     * Normally navigation could be written directly to the template,
     * but for now we can keep it here.
     *
     * In future version this could be loaded from it's own configuration file.
     * 
     * > title - Title of this navigation item. Currently they are stored in translations file. [Mandatory]
     * > route - The system generates the URL based on the provided route name. [Mandatory]
     * > icon -  Display an icon before the title. This theme supports font-awesome and glyphicons. [Optional]
     * > roles - An array of roles specifiyng who can see this navigation item. 
     *
     * @return array
     */
    private function getNavigationLinks() {
        $items = array(
            'dashboard' => array(
                'title' => 'nav.dashboard', 
                'icon' => 'fa fa-dashboard', 
                'route' => 'homepage'
            ),
            'projects' => array(
                'title' => 'nav.projects', 
                'icon' => 'fa fa-edit', 
                'route' => 'projects_list'
            ),
            'users' => array(
                'title' => 'nav.users', 
                'icon' => 'fa fa-user', 
                'route' => 'users_list', 
                'roles' => array('ROLE_ADMIN')
            ),
            'title' => array(
                'title' => 'nav.categories', 
                'icon' => 'fa fa-book', 
                'route' => 'categories_list', 
                'roles' => array('ROLE_ADMIN')
            ),
            'priorities' => array(
                'title' => 'nav.priorities', 
                'icon' => 'fa fa-exclamation-triangle', 
                'route' => 'priorities_list', 
                'roles' => array('ROLE_ADMIN')
            ),
            'statuses' => array(
                'title' => 'nav.statuses', 
                'icon' => 'fa fa-list', 
                'route' => 'statuses_list', 
                'roles' => array('ROLE_ADMIN')
            ),
            'trackers' => array(
                'title' => 'nav.trackers', 
                'route' => 'trackers_list', 
                'roles' => array('ROLE_ADMIN')
            ),
            'roles' => array(
                'title' => 'nav.roles', 
                'icon' => 'fa fa-database', 
                'route' => 'roles_list', 
                'roles' => array('ROLE_ADMIN')
            )
        );
        
        return $items;
    }

}
