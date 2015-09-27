<?php

namespace Tracker\Widget;

abstract class AbstractWidget {

    /**
     *
     * @var \Twig_Environment
     */
    private $twig;

    /**
     *
     * @var array
     */
    public $params = array();

    /**
     *
     * @param \Twig_Environment $twig
     * @return \Tracker\Widget\AbstractWidget
     */
    public function setTwig(\Twig_Environment $twig) {
        $this->twig = $twig;

        return $this;
    }

    /**
     *
     * @param array $params
     * @return \Tracker\Widget\AbstractWidget
     */
    public function setParams(array $params) {
        $this->params = $params;

        return $this;
    }

    /**
     * Apply a before filter. Should be overriden by child widget only.
     */
    public function beforeLoad() {}

    /**
     *
     * @param string $template
     * @param array $params
     * @return string
     */
    final public function render($template, array $params = array()) {
        return $this->twig->render($template, $params);
    }

}
