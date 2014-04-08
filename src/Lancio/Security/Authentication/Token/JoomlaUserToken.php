<?php

namespace Lancio\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class JoomlaUserToken extends AbstractToken
{
    public $credentials = "";
    
    public function __construct($user, $credentials, array $roles = array())
    {
        parent::__construct($roles);
        
        $this->setUser($user);
        $this->credentials = $credentials;
        
        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return $this->credentials;
    }
}