<?php

namespace Lancio\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
//use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Lancio\Security\Authentication\Token\JoomlaUserToken;



class JoomlaProvider implements AuthenticationProviderInterface
{
    private $userProvider;
    private $cacheDir;

    public function __construct(UserProviderInterface $userProvider, $cacheDir)
    {
        $this->userProvider = $userProvider;
        $this->cacheDir     = $cacheDir;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());
//var_dump($user);
        if ($user && $this->validatePassword($token->getCredentials(), $user->getPassword())) {
//            $authenticatedToken = new JoomlaUserToken($user->getRoles());
            
            $authenticatedToken = new JoomlaUserToken($user, $user->getPassword() ,$user->getRoles());
//            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The Joomla authentication failed.');
    }

    protected function validatePassword($givenPassword, $userPassword)
    {
        $hashparts = preg_split ('/:/' , $userPassword);

        $userhash = md5($givenPassword.$hashparts[1]); 
        
        return $userhash === $hashparts[0];
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof JoomlaUserToken;
    }
}
