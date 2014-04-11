<?php

namespace Lancio\Cambuse\Entity;

/**
 * Description of User
 *
 * @author lancio
 */
class Order
{
    protected $id; 
    protected $user; 
    protected $name; 
    protected $surname; 
    protected $scout_group; 
    protected $phone; 
    protected $email;
    protected $products;
    protected $total;

    public function __construct(User $user, \Doctrine\Common\Collections\ArrayCollection $products )
    {
        if (!count($products)) {
            throw new \InvalidArgumentException('l\'ordine deve contenere almeno un prodotto');
        }

        $this->name= $user->getName();
        $this->surname = $user->getSurname();
        $this->scout_group = $user->getScoutGroup();
        $this->unit = $user->getUnit();
        $this->phone = $user->getPhone();
        $this->email = $user->getEmail();
        $this->user = $user;
        $this->products = $products;
        
        $this->total = 0;
        foreach ($products as $product) {
            $this->total = $product->getTotal();
        }
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function getProducts()
    {
        return $this->products;
    }
    
    public function getTotal()
    {
        return $this->total;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    public function getUser()
    {
        return $this->user;
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
    public function getPhone()
    {
        return $this->phone;
    }
    public function getEmail()
    {
        return $this->email;
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
}
