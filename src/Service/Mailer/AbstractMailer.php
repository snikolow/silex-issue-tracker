<?php

namespace App\Service\Mailer;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractMailer {
    
    /** @var null */
    private $transport = null;
    
    /** @var \Swift_Message */
    private $message = null;
    
    /** @var array */
    private $options = array();
    
    /**
     * 
     * @param array $options
     * @return array
     */
    public function configureOptions(array $options = array()) {
        $resolver = new OptionsResolver();
        
        $resolver->setDefaults(
                array(
                    'adapter' => 'default',
                    'host' => $this->getHost(),
                    'username' => null,
                    'password' => null,
                    'port' => $this->getPort(),
                    'auth_mode' => $this->getAuthMode(),
                    'encryption' => $this->getEncryption()
                )
        );
        
        $this->options = $resolver->resolve($options);
        
        return $this->options;
    }
    
    public function setTransport($transport) {
        $this->transport = $transport;
    }
    
    /**
     * 
     * @return \App\Service\Mailer
     */
    public function newMessage() {
        $this->message = \Swift_Message::newInstance();
        
        return $this;
    }
    
    /**
     * 
     * @param string $from
     * @return \App\Service\Mailer
     */
    public function setFrom($from) {
        $this->message->setFrom($from);
        
        return $this;
    }
    
    /**
     * 
     * @param string $address
     * @return \App\Service\Mailer
     */
    public function setTo($address) {
        $this->message->addTo($address);
        
        return $this;
    }
    
    /**
     * 
     * @param string $subject
     * @return \App\Service\Mailer
     */
    public function setSubject($subject) {
        $this->message->setSubject($subject);
        
        return $this;
    }
    
    /**
     * 
     * @param string $content
     * @return \App\Service\Mailer
     */
    public function setBody($content) {
        $this->message->setBody($content);
        
        return $this;
    }
    
    public function send() {
        $mailer = \Swift_Mailer::newInstance($this->transport);
        
        return $mailer->send($this->message);
    }
    
}