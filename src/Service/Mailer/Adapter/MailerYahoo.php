<?php

namespace Tracker\Service\Mailer\Adapter;

use Tracker\Service\Mailer\AbstractMailer;
use Tracker\Service\Mailer\MailerInterface;

class MailerYahoo extends AbstractMailer implements MailerInterface {
    
    public function getHost() {
        return 'smtp.mail.yahoo.com';
    }
    
    public function getPort() {
        return 465;
    }
    
    public function getAuthMode() {
        return null;
    }
    
    public function getEncryption() {
        return 'ssl';
    }
    
}