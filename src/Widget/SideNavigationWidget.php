<?php

namespace App\Widget;

class SideNavigationWidget extends AbstractWidget {

    public function getContent() {
        return $this->render('widget/sideNavigation.twig',
            array(
                'collection' => $this->getNavigationLinks()
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
     * @return array
     */
    private function getNavigationLinks() {
        return array(
            array('title' => 'nav.dashboard', 'icon' => 'fa fa-dashboard', 'route' => 'homepage'),
            array('title' => 'nav.projects', 'icon' => 'fa fa-edit', 'route' => 'projects_list'),
            array('title' => 'nav.users', 'icon' => 'fa fa-user', 'route' => 'users_list'),
            array('title' => 'nav.priorities', 'icon' => 'fa fa-exclamation-triangle', 'route' => 'priorities_list'),
            array('title' => 'nav.statuses', 'icon' => 'fa fa-list', 'route' => 'statuses_list'),
            array('title' => 'nav.trackers', 'route' => 'trackers_list'),
            array('title' => 'nav.roles', 'icon' => 'fa fa-database', 'route' => 'roles_list')
        );
    }

}
