<?php

namespace Tracker\Service\Mailer\Adapter;

use Tracker\Service\Mailer\AbstractMailer;
use Tracker\Service\Mailer\MailerInterface;

class MailerDefault extends AbstractMailer implements MailerInterface {
    
    public function getHost() {
        return 'mailer.host.com';
    }
    
    public function getPort() {
        return 25;
    }
    
    public function getAuthMode() {
        return null;
    }
    
    public function getEncryption() {
        return null;
    }
    
}