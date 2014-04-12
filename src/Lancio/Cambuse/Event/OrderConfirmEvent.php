<?php

namespace Lancio\Cambuse\Event;

use Symfony\Component\EventDispatcher\Event;
use Lancio\Cambuse\Entity\User;

/**
 * Description of OrderConfirmEvent
 *
 * @author lancio
 */
class OrderConfirmEvent extends Event 
{   
    protected $order; 
    protected $shop = "pachamama";
    
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    public function setShop($shop)
    {
        $this->shop = $shop;
        return $this;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
    
    public function getShop()
    {
        return $this->shop;
    }
}
