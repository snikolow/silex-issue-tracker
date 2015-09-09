<?php

namespace App\Service\Mailer\Adapter;

use App\Service\Mailer\AbstractMailer;
use App\Service\Mailer\MailerInterface;

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