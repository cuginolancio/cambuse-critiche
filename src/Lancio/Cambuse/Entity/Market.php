<?php

namespace Lancio\Cambuse\Entity;

/**
 * Description of User
 *
 * @author lancio
 */
class Market
{
    protected $id; 
    protected $name; 
    protected $city; 
    protected $address;
    protected $description;
    protected $phone;
    protected $mobile;
    protected $email;
    protected $link;
    protected $site;
    protected $active;
    protected $products;

    static public function loadFromArray(array $market)
    {
        $prod = new static();
        
        $prod->setId($market['id'])
            ->setName($market['name'])
            ->setCity($market['city'])
            ->setAddress($market['address'])
            ->setDescription($market['description'])
            ->setPhone($market['phone'])
            ->setMobile($market['mobile'])
            ->setEmail($market['email'])
            ->setLink($market['link'])
            ->setSite($market['site'])
            ->setActive($market['active']);
        
        return $prod; 
    }
    public function __construct()
    {
        $this->products = [];
    }

    public function getId()
    {
        return $this->id;
    }
    public function getSite()
    {
        return $this->site;
    }
    public function getLink()
    {
        return $this->link;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getCity()
    {
        return $this->city;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getMobile()
    {
        return $this->mobile;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function getProducts()
    {
        return $this->products;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setSite($site)
    {
        $this->site = $site;
        return $this;
    }
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }
    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }
}
