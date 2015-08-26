<?php

namespace App\Provider\ConsoleProvider\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Silex\Application as SilexApplication;

class ConsoleApplication extends BaseApplication {
    
    const VERSION = '1.0';
    const NAME = 'Console App';
    
    /** @var SilexApplication */
    private $silexApplication;

    public function __construct(SilexApplication $application) {
        parent::__construct(self::NAME, self::VERSION);
        $this->silexApplication = $application;
        
        $application->boot();
    }

    public function getSilexApplication() {
        return $this->silexApplication;
    }

}
