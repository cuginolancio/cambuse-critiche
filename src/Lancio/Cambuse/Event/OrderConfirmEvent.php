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
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }
    
    public function getOrder()
    {
        return $this->order;
    }
}
