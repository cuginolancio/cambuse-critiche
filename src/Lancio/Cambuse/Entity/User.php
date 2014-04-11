<?php

namespace Lancio\Cambuse\Entity;

/**
 * Description of User
 *
 * @author lancio
 */
class User implements \Symfony\Component\Security\Core\User\AdvancedUserInterface
{
    private $id;
    private $username;
    private $password;
    private $enabled;
    private $accountNonExpired;
    private $credentialsNonExpired;
    private $accountNonLocked;
    private $roles;
    
    protected $name; 
    protected $surname; 
    protected $scout_group; 
    protected $unit; 
    protected $phone; 
    protected $email;
    protected $salt;

    static public function loadFromArray(array $userData, $groups)
    {
        $user = new self($userData['username'], $userData['password'], $groups, true, true, true, true);
        
        $user->setId($userData['id'])
                ->setEmail($userData['email'])
                ->setPhone($userData['cb_telcell'])
                ->setName($userData['firstname'])
                ->setSurname($userData['lastname'])
                ->setUnit($userData['cb_unita'])
                ->setScoutGroup($userData['cb_gruppo']);
        return $user; 
    }
    public function __construct($username, $password, array $roles = array(), $enabled = true, $userNonExpired = true, $credentialsNonExpired = true, $userNonLocked = true)
    {
        if (empty($username)) {
            throw new \InvalidArgumentException('The username cannot be empty.');
        }

        $this->username = $username;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->accountNonExpired = $userNonExpired;
        $this->credentialsNonExpired = $credentialsNonExpired;
        $this->accountNonLocked = $userNonLocked;
        $this->roles = $roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        $matches = preg_split("/:/", $this->password);
        return $matches[1];
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return $this->accountNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return $this->accountNonLocked;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return $this->credentialsNonExpired;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getSurname()
    {
        return $this->surname;
    }
    public function getScoutGroup()
    {
        return $this->scout_group;
    }
    public function getUnit()
    {
        return $this->unit;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }
    public function setScoutGroup($group)
    {
        $this->scout_group = $group;
        return $this;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    public function setUnit($unit)
    {
        $this->unit = $unit;
        return $this;
    }
    
//    public function __toString()
//    {
//        return $this->username;
//    }
}
