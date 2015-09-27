<?php

namespace Tracker\Service\Mailer\Adapter;

use Tracker\Service\Mailer\AbstractMailer;
use Tracker\Service\Mailer\MailerInterface;

class MailerGmail extends AbstractMailer implements MailerInterface {
    
    public function getHost() {
        return 'smtp.gmail.com';
    }
    
    public function getPort() {
        return 465;
    }
    
    public function getAuthMode() {
        return 'login';
    }
    
    public function getEncryption() {
        return 'ssl';
    }
    
}