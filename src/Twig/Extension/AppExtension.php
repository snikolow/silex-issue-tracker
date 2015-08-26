<?php

namespace App\Twig\Extension;

use Symfony\Component\HttpFoundation\Request;
use App\Service\DateTimeHelper;

class AppExtension extends \Twig_Extension {

    /** @var Request */
    private $request;

    /** @var \Twig_Environment */
    private $twig;
    
    /** @var DateTimeHelper */
    private $dateTimeHelper;

    /** @var array */
    private $widgets = array();

    /** @var array */
    private $safeOptionsArray = array(
        'is_safe' => array('html')
    );

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array
     */
    public function __construct(Request $request, DateTimeHelper $helper, array $widgets = array()) {
        $this->request = $request;
        $this->dateTimeHelper = $helper;
        $this->widgets = $widgets;
    }

    /**
     *
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twig = $environment;

        return parent::initRuntime($environment);
    }

    /**
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'asset'     => new \Twig_SimpleFunction(
                    'asset', array($this, 'getAssetUrl')
            ),
            'widget'     => new \Twig_SimpleFunction(
                    'widget', array($this, 'renderWidget'), $this->safeOptionsArray
            ),
            'yesNo'     => new \Twig_SimpleFunction(
                    'yesNo', array($this, 'yesNo'), $this->safeOptionsArray
            ),
            'timeAgo'   => new \Twig_SimpleFunction(
                    'timeAgo', array($this, 'timeAgo'), $this->safeOptionsArray
            )
        );
    }

    /**
     *
     * @param string $asset
     * @return string
     */
    public function getAssetUrl($asset) {
        return sprintf('%s/%s',
                $this->request->getBasePath(),
                ltrim($asset, '/')
        );
    }

    /**
     *
     * @param string $id
     * @param array $params
     * @return string
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function renderWidget($id, array $params = array()) {
        if( count($this->widgets) && isset($this->widgets[ $id ]) ) {
            $config = $this->widgets[ $id ];

            if( class_exists($config['class']) ) {
                $widget = new $config['class']();

                if( isset($config['calls']) ) {
                    foreach($config['calls'] as $method => $argument) {
                        call_user_func_array(
                                array($widget, $method),
                                array($argument)
                        );
                    }
                }

                $widget
                    ->setTwig($this->twig)
                    ->setParams($params)
                    ->beforeLoad();

                return $widget->getContent();
            }
            else {
                throw new \Exception(sprintf(
                        'Class %s not found!',
                        $config['class']
                ));
            }
        }
        else {
            throw new \RuntimeException('There are no defined blocks!');
        }
    }
    
    /**
     * 
     * @param mixed $value Could be either boolean or intger (1|0)
     * @return string
     */
    public function yesNo($value) {
        $text = ((bool) $value === true) ? 'Yes' : 'No';
        
        return $text;
    }
    
    /**
     * 
     * @param mixed $date Could be either \DateTime object or formatted string
     * @return string
     */
    public function timeAgo($date) {
        if( ! $date instanceof \DateTime ) {
            $date = new \DateTime($date);
        }
        
        $timestamp = $date->getTimestamp();
        
        return $this->dateTimeHelper->getTimeAgo($timestamp);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app.extension';
    }

}
