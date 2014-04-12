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
    protected $user_id; 
    protected $name; 
    protected $surname; 
    protected $scout_group; 
    protected $scout_unit; 
    protected $phone; 
    protected $email;
    protected $note;
    protected $products;
    protected $total;
    protected $datetime;
    protected $accept;
    protected $n_items;

    public function __construct($shop, User $user, \Doctrine\Common\Collections\ArrayCollection $products )
    {
        if (!count($products)) {
            throw new \InvalidArgumentException('l\'ordine deve contenere almeno un prodotto');
        }

        $this->shop = $shop;
        $this->user_id = $user->getId();
        $this->name = $user->getName();
        $this->surname = $user->getSurname();
        $this->scout_group = $user->getScoutGroup();
        $this->scout_unit = $user->getScoutUnit();
        $this->phone = $user->getPhone();
        $this->email = $user->getEmail();
        $this->user = $user;
        $this->products = $products;
        
        $this->calculateTotal();
        
    }

    static public function loadFromArray(array $orderArray, \Doctrine\Common\Collections\ArrayCollection $products = null)
    {
        $userArray = [
            'id' => $orderArray['user_id'],
            'firstname' => $orderArray['name'],
            'lastname' => $orderArray['surname'],
            'email' => $orderArray['email'],
            'cb_telcell' => $orderArray['phone'],
            'cb_gruppo' => $orderArray['scout_group'],
            'cb_unita' => $orderArray['scout_unit'],
            'username' => ".",
            'password' => "",
        ];
        $user = User::loadFromArray($userArray, []);
        
        $order = new self($orderArray['shop'], $user, $products);
        $order->setId($orderArray['id'])
                ->setNote($orderArray['note'])
                ->setDatetime(new \Datetime($orderArray['datetime']))
                ->setAccept($orderArray['accept']);
                
        return $order; 
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getShop()
    {
        return $this->shop;
    }
    
    public function getProducts()
    {
        return $this->products;
    }
    
    public function getTotal($formatted = false)
    {
        if ($formatted)
            return number_format($this->total, 2);
        
        return $this->total;
    }

    public function getScoutUnit()
    {
        return $this->scout_unit;
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
    public function getNote()
    {
        return $this->note;
    }
    public function getAccept()
    {
        return $this->accept;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
    public function getDatetime()
    {
        return $this->datetime;
    }
    public function getCountItems()
    {
        return $this->n_items;
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
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }
    public function setAccept($accept)
    {
        $this->accept = $accept;
        return $this;
    }
    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }
    public function setDatetime(\Datetime $datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }
    public function calculateTotal() 
    {
        $this->total = 0;
        $this->n_items = 0;
        foreach ($this->products as $product) {
            $this->total += $product->getTotal();
            $this->n_items += $product->getQuantity();
        }
        return $this->total;
    }
}
