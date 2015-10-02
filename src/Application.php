<?php

namespace Tracker;

use Silex\Application as BaseApplication;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Symfony\Component\HttpKernel\KernelEvents;
use Tracker\Twig\Extension\TrackerExtension;
use Tracker\Component\Security\Provider\UserProvider;
use Tracker\Component\Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;

class Application extends BaseApplication {

    use \Silex\Application\TwigTrait;
    use \Silex\Application\TranslationTrait;
    use \Silex\Application\UrlGeneratorTrait;
    use \Silex\Application\SecurityTrait;
    use \Silex\Application\FormTrait;

    public function configureOptions() {
        $app = $this;

        $this['resolver'] = $this->share(function() use ($app) {
            return new ControllerResolver($app, null);
        });

        /**
         * Configure application providers
         */
        $config = Yaml::parse( Helper\ConfigHelper::getContents('application.yml') );

        // Merge user defined configuration with some pre-defined values.
        $userConfig = array_merge(
            Yaml::parse( Helper\ConfigHelper::getContents('config.yml') ),
            array(
                'ROOT_PATH' => ROOT_PATH,
                'debug' => $this['debug']
            )
        );

        if( ! $debugMode = Helper\ConfigHelper::getConfigItem('app.debug', $userConfig) ) {
            $debugMode = false;
        }

        $this['debug'] = $debugMode;

        // Replace %config.item% with it's defined value from configuration files.
        array_walk_recursive($config, 'Tracker\Helper\ConfigHelper::replaceParameters', $userConfig);

        // Register all of the defined providers
        if( isset($config['providers']) && is_array($config['providers']) ) {
            foreach($config['providers'] as $provider => $providerConfig) {
                $options = array();

                $providerInstance = new $providerConfig['class']();

                // If there are parameters defined for the current provider
                // store them in $options array and pass them to the provider
                // as an argument.
                if( isset($providerConfig['params']) ) {
                    // We are doing an extra check here for Security provider.
                    // Since it requires a custom defined UserProvider in order
                    // to query the users from database.
                    // In future version this should be changed with some less
                    // "hack-ish" code.
                    if( $provider === 'security' ) {
                        array_walk_recursive($providerConfig['params'], function(&$item, $key) use ($app) {
                            if( $key === 'users' ) {
                                $item = $app->share(function() use ($app) {
                                    return new UserProvider($app['orm.em']->getRepository('Tracker\Entity\User'));
                                });
                            }
                        });
                    }

                    $options = $providerConfig['params'];
                }

                // Register the current provider
                $this->register($providerInstance, $options);
            }
        }

        // Register application specific widgets
        if( isset($config['widgets']) ) {
            $this['widgets'] = $this->share(function() use ($config, $app) {
                $widgets = $config['widgets'];

                foreach($widgets as $id => $widget) {
                    // Check if a widget requires some extra services
                    // from application as a dependencies.
                    if( isset($widget['calls']) && is_array($widget['calls']) ) {
                        foreach($widget['calls'] as $method => $param) {
                            if( substr($param, 0, 1) === '@' ) {
                                $service = str_replace('@', '', $param);

                                // If found, we replace the string representation
                                // of the service with it's instance added in $app.
                                $widgets[ $id ]['calls'][ $method ] = $app[ $service ];
                            }
                        }
                    }
                }

                return $widgets;
            });
        }

        // Register translation files.
        // Currently it loads a single file only - the one responsible
        // for the defined locale. If no locale is provided from the
        // user configuration, "en" will be set as a default one.
        // The application also ships with a translation for it.
        if( isset($this['translator']) ) {
            if( ! $locale = Helper\ConfigHelper::getConfigItem('app.locale', $userConfig) ) {
                $locale = 'en';
            }

            // A callback to locate the selected translation file.
            $translationLocator = function($locale, $ext = 'yml') {
                    return sprintf(
                        '%s/app/translations/%s.trans.%s',
                        ROOT_PATH,
                        $locale,
                        $ext
                    );
            };

            // If the file exists, add it to translator.domains
            // Normally there is a more convinient way to register
            // translation domains, by using YamlTranslationLoader with
            // ->addResource method.
            if( file_exists($translationLocator($locale)) ) {
                $translation = file_get_contents($translationLocator($locale));
                $this['translator.domains'] = array(
                    'messages' => array(
                        $locale => Yaml::parse($translation)
                    )
                );
            }
        }

        // Register application defined security voters.
        if( isset($config['security_voters']) && count($config['security_voters']) ) {
            $this['security.voters'] = $this->extend('security.voters', function($voters) use ($config) {
                foreach($config['security_voters'] as $voterData) {
                    $voters[] = new $voterData['class'];
                }

                return $voters;
            });
        }

        /**
         * Configure routes
         */
        $app['routes'] = $app->extend('routes', function(RouteCollection $collection) {
            $locator = new FileLocator(ROOT_PATH . '/app/config');
            $loader = new YamlFileLoader($locator);
            $routes = $loader->load('routing.yml');

            $collection->addCollection($routes);

            return $collection;
        });

        // Register Twig Extensions
        $this->loadTwigExtensions();

        // Register Form Types/Extensions
        $this->loadFormExtensions();

        // Register internal services
        $this->registerInternalServices();

        // Register internal events
        $this->registerEventListeners();

        // Register Unique Entity validator
        $this->registerUniqueEntityValidator();
         
         // Register error handlers to display a friendly error message
         // when the project is not running in debug mode.
         $this->registerErrorHandlers();
    }

    private function loadTwigExtensions() {
        $app = $this;
        /**
        * Register custom Twig extension that provides simple functionality,
        * like defining a asset() function, or widget() function created for
        * this project.
        */
        $app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
            /* @var $twig \Twig_Environment */
            $appExtension = new TrackerExtension(
                    $app['request_stack'],
                    $app['widgets']
            );

            $twig->addExtension($appExtension);

            return $twig;
        }));
    }

    private function loadFormExtensions() {
        $app = $this;

        $this['form.extensions'] = $this->share($app->extend('form.extensions', function ($extensions, $app) {
            // Register doctrine form extension that would allow us to use "entity" form type.
            $manager = $app['managerRegistry'];
            $extensions[] = new DoctrineOrmExtension($manager);

            return $extensions;
        }));
    }

    private function registerInternalServices() {
        $app = $this;

        $this['breadcrumbs'] = $this->share(function() {
            return new Service\Breadcrumb();
        });

        $this['datetime'] = $this->share(function() {
            return new Service\DateTimeHelper();
        });

        $this['paginator'] = $this->share(function() {
            return new Service\Paginator();
        });

        $this['managerRegistry'] = $this->share(function() use ($app) {
            $manager = new ManagerRegistry(
                null, array(), array('orm.em'), null, 'orm.em', '\Doctrine\ORM\Proxy\Proxy'
            );

            $manager->setContainer($app);

            return $manager;
        });
    }

    private function registerEventListeners() {
        /* @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcher */
        $dispatcher = $this['dispatcher'];

        $beforeActionListener = new Event\Listener\BeforeActionListener();
        $dispatcher->addListener(KernelEvents::CONTROLLER, array($beforeActionListener, 'onKernelController'));
    }
    
    private function registerUniqueEntityValidator() {
        $app = $this;
        
        if( isset($this['validator']) &&  class_exists('Symfony\\Bridge\\Doctrine\\Validator\\Constraints\\UniqueEntityValidator') ) {
            $this['doctrine.orm.validator.unique_validator'] = $this->share(function ($app) {
                return new UniqueEntityValidator($app['managerRegistry']);
            });
            
            if( ! isset($this['validator.validator_service_ids']) ) {
                $this['validator.validator_service_ids'] = array();
            }
            
            $this['validator.validator_service_ids'] = array_merge(
                $this['validator.validator_service_ids'],
                array('doctrine.orm.validator.unique' => 'doctrine.orm.validator.unique_validator')
            );
        }
    }
    
    private function registerErrorHandlers() {
        if( ! $this['debug'] ) {
            $app = $this;
        
            $app->error(function(\Exception $ex, $code) use ($app) {
                $mappings = array(
                    403 => array(
                        'code'      => 403,
                        'title'     => 'Access denied!',
                        'content'   => 'You don\'t have permissions to view this page!'
                    ),
                    404 => array(
                        'code'      => 404,
                        'title'     => 'Requested page not found!',
                        'content'   => 'The page you are looking for was moved, removed, renamed or might never existed.'
                    ),
                    500 => array(
                        'code'      => 500,
                        'title'     => 'Oops! Something went wrong!',
                        'content'   => 'We are experiencing some problems right now. Please, try again later!'
                    )
                );

                if( isset($mappings[ $code ]) ) {
                    $content = $app['twig']->render('layouts/error_handler.twig',
                            array(
                                'code'      => $code,
                                'title'     => $mappings[ $code ]['title'],
                                'content'   => $mappings[ $code ]['content']
                            )
                    );

                    return new Response($content, $code, array('X-Status-Code' => $code));
                }
            });
        }
    }

}
