<?php

namespace Lancio\Cambuse\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Lancio\Cambuse\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\DBAL\Connection;

class UserProvider implements UserProviderInterface
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }
    
    public function loadUserByUsername($username)
    {
        $stmt = $this->conn->executeQuery('SELECT * '
                . 'FROM j25_users u '
                . 'INNER JOIN j25_comprofiler p ON (u.id = p.user_id) '
                . 'WHERE username = ?', array(strtolower($username)));

        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
        $roles = $this->loadRolesForUser($user);
        
        return User::loadFromArray($user, $roles);
    }

    public function loadRolesForUser($user)
    {
        $roles = [];
        $stmt = $this->conn->executeQuery('SELECT group_id '
                . 'FROM j25_user_usergroup_map WHERE user_id = ?', array($user['id']));
        $groups = $stmt->fetchAll();
        
        foreach ($groups as $group) {
            switch ($group['group_id']) {
                case '8':
                    $roles[] = "ROLE_ADMIN";
                    break;
                case '9':
                    $roles[] = "ROLE_USER";
                    break;
                default: 
            }
        }
        return $roles; 
    }
        
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'Lancio\Cambuse\Entity\User';
    }

}
