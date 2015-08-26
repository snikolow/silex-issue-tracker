<?php

namespace App\Component;

use Silex\Application as BaseApplication;
use Silex\Application\TwigTrait;
use Silex\Application\TranslationTrait;
use Silex\Application\UrlGeneratorTrait;
use Silex\Application\SecurityTrait;
use Silex\Application\FormTrait;
use App\Component\ControllerResolver;

class Application extends BaseApplication {

    use TwigTrait;
    use TranslationTrait;
    use UrlGeneratorTrait;
    use SecurityTrait;
    use FormTrait;

    /**
     * Register custom controller resolver that would inject Application instance in BaseController
     * to be access in all controllers when it's needed.
     */
    public function initControllerResolver() {
        $app = $this;

        $this['resolver'] = $this->share(function() use ($app) {
            return new ControllerResolver($app, null);
        });
    }

}
