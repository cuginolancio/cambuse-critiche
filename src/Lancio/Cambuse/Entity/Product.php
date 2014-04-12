<?php

namespace Lancio\Cambuse\Entity;

/**
 * Description of User
 *
 * @author lancio
 */
class Product
{
    protected $id; 
    protected $code; 
    protected $name; 
    protected $price; 
    protected $price_regular;
    protected $active;
    protected $category_id;
    protected $category;
    protected $quantity;
    protected $bio;
    protected $pieces_in_package;

    static public function loadFromArray(array $product, $quantity = 0)
    {
        $prod = new static();
        
        $prod->setId($product['id'])
            ->setCode($product['code'])
            ->setName($product['name'])
            ->setPrice($product['price'])
            ->setPriceRegular($product['price_regular'])
            ->setCategoryId($product['category_id'])
            ->setCategory($product['category'])
            ->setPiecesInPackage($product['pieces_in_package'])
            ->setQuantity($quantity)
            ->setBio($product['bio'])
            ->setActive($product['active']);
        
        return $prod; 
    }
    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }
    public function getCode()
    {
        return $this->code;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getBio()
    {
        return $this->bio;
    }
    public function getPiecesInPackage()
    {
        return $this->pieces_in_package;
    }
    public function getPrice($formatted = false)
    {
        if ($formatted)
            return number_format($this->price, 2);
        
        return $this->price;
    }
    public function getPriceRegular($formatted = false)
    {
        if ($formatted)
            return number_format($this->price_regular, 2);
        
        return $this->price_regular;
    }
    public function getCategoryId()
    {
        return $this->category_id;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getTotal($formatted = false)
    {
        $total = $this->quantity * $this->price;
        if ($formatted)
            return number_format($total, 2);
        
        return $total;
    }
    
    public function setBio($bio)
    {
        $this->bio = $bio;
        return $this;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    public function setPiecesInPackage($pieces)
    {
        $this->pieces_in_package = $pieces;
        return $this;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setCategoryId($id)
    {
        $this->category_id = $id;
        return $this;
    }
    public function setCategory($cat)
    {
        $this->category = $cat;
        return $this;
    }
    public function setQuantity($qty)
    {
        $this->quantity = $qty;
        return $this;
    }
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    public function setPriceRegular($price)
    {
        $this->price_regular = $price;
        return $this;
    }
}
