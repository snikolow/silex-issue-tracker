<?php

use App\Service;
use App\Component\Doctrine\Common\Persistence\ManagerRegistry;

if( isset($app) ) {
    $app['breadcrumbs'] = $app->share(function() {
        return new Service\Breadcrumb();
    });
    
    $app['datetime'] = $app->share(function() {
        return new Service\DateTimeHelper();
    });
    
    $app['paginator'] = $app->share(function() {
        return new Service\Paginator();
    });
    
    $app['app.mailer'] = $app->share(function() use ($app) {
        $config = isset($app['project.config']['mailer']) ? $app['project.config']['mailer'] : array();
        
        if( isset($config['adapter']) && $config['adapter'] === 'gmail' ) {
            unset($config['host']);
            $mailer = new Service\Mailer\Adapter\MailerGmail();
        }
        elseif( isset($config['adapter']) && $config['adapter'] === 'yahoo') {
            unset($config['host']);
            $mailer = new Service\Mailer\Adapter\MailerYahoo();
        }
        else {
            $mailer = new Service\Mailer\Adapter\MailerDefault();
        }
        
        $options = $mailer->configureOptions($config);
        
        $transport = $app['swiftmailer.transport'];
        $transport->setHost($options['host']);
        $transport->setPort($options['port']);
        $transport->setEncryption($options['encryption']);
        $transport->setUsername($options['username']);
        $transport->setPassword($options['password']);
        $transport->setAuthMode($options['auth_mode']);
        
        $mailer->setTransport($transport);
        
        return $mailer;
    });
    
    $app['managerRegistry'] = $app->share(function() use ($app) {
        $manager = new ManagerRegistry(
            null, array(), array('orm.em'), null, 'orm.em', '\Doctrine\ORM\Proxy\Proxy'
        );
        
        $manager->setContainer($app);
        
        return $manager;
    });
}
