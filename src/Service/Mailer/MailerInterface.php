<?php

namespace Tracker\Service\Mailer;

/**
 * Implement the following interface if more email providers are needed.
 */
interface MailerInterface {
    
    /**
     * Return the host that we would try to connect, like - mail.host.com
     * Returning a value is mandatory.
     * 
     * @return string
     */
    public function getHost();
    
    /**
     * Return the port that is required to connect.
     * 
     * @return int
     */
    public function getPort();
    
    /**
     * The authentication mode to use when using smtp as the transport. Valid values are plain, login, cram-md5, or null.
     * 
     * @return string|null
     */
    public function getAuthMode();
    
    /**
     * The encryption mode to use when using smtp as the transport. Valid values are tls, ssl, or null (indicating no encryption).
     * 
     * @return string|null
     */
    public function getEncryption();
    
}