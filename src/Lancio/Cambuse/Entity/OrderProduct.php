<?php

namespace Lancio\Cambuse\Entity;

/**
 * Description of User
 *
 * @author lancio
 */
class OrderProduct extends Product
{
    protected $order_product_id;
    protected $order_id;
    protected $user_id;
    protected $quantity;
    protected $total;

    static public function loadFromArray(array $product, $quantity = 0)
    {
        $product['active'] = true;
        $prod = parent::loadFromArray($product, $quantity);
        
        $prod->setActive(true)
            ->setQuantity($product['quantity'])
            ->setOrderId($product['order_id'])
            ->setUserId($product['user_id'])
            ->setTotal($product['total']);
        
        return $prod; 
    }
    
    public function getTotal($formatted = false)
    {
        if ($formatted)
            return number_format($this->total, 2);
        
        return $this->total;
    }
    
    public function getUserId()
    {
        return $this->user_id;
    }
    
    public function getOrderId()
    {
        return $this->order_id;
    }
    public function getOrderProductId()
    {
        return $this->order_product_id;
    }
    
    public function getQuantity()
    {
        return $this->quantity;
    }
    
    public function setOrderProductId($orderProductId)
    {
        $this->order_product_id = $orderProductId;
        return $this;
    }
    
    public function setUserId($userId)
    {
        $this->user_id = $userId;
        return $this;
    }
    public function setOrderId($orderId)
    {
        $this->order_id = $orderId;
        return $this;
    }
    
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
    
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }
}
