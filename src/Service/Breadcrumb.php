<?php

namespace App\Service;

class Breadcrumb {

    /** @var array */
    private $elements = array();

    /**
     * Add new item to collection
     *
     * @param string $title
     * @param string|null $route
     * @param array $params
     * @return Bradcrumb
     */
    public function add($title, $route = null, array $params = array()) {
        $this->elements[] = array(
            'title'     => $title,
            'route'     => $route,
            'params'    => $params
        );

        return $this;
    }

    /**
     * Return all items
     *
     * @return array
     */
    public function getElements() {
        return $this->elements;
    }

}
