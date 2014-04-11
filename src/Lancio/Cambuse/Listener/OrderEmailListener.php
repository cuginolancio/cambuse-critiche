<?php

namespace Lancio\Cambuse\Listener;

use Silex\Application;
use Lancio\Cambuse\Event\OrderConfirmEvent;
/**
 * Description of SendOrderEmailListener
 *
 * @author lancio
 */
class OrderEmailListener {
    
    public function __construct(Application $app )
    {
        $this->mailer = $app['mailer'];
    }
    
    public function sendToCustomer(OrderConfirmEvent $e )
    {
        $order = $e->getOrder();
        $user = $order->getUser();
        $msgProduct = "";
        
        foreach ($order->getProducts() as $product) {
            $msgProduct .= $product->getCode() . " - ". $product->getName() . "\n"
                    . " € ". $product->getPrice() . " X " .$product->getQuantity() . " = " . number_format(($product->getPrice() * $product->getQuantity()), 2)   ;
        }
        
        $msg = <<<MSG
    Ordine: 
        
MSG;
        
        $message = \Swift_Message::newInstance()
        ->setSubject('[CambuseCritiche] Ordine n' . $order->getId())
        ->setFrom(array($user->getEmail()))
        ->setTo(array($user->getEmail()))
        ->setBody($msg);

        $this->mailer->send($message);
    }
    
    public function sendToBusiness(OrderConfirmEvent $e )
    {
        
        $order = $e->getOrder();
        $user = $order->getUser();
        $msgProduct = "";
        
        foreach ($order->getProducts() as $product) {
            $msgProduct .= $product->getCode() . " - ". $product->getName() . "\n"
                    . " € ". $product->getPrice() . " X " .$product->getQuantity() . " = " . number_format(($product->getPrice() * $product->getQuantity()), 2)   ;
        }
        
        $msg = <<<MSG
    Ordine: 
        
MSG;
        
        $message = \Swift_Message::newInstance()
        ->setSubject('[CambuseCritiche] Ordine n' . $order->getId())
        ->setFrom(array($user->getEmail()))
        ->setTo(array($user->getEmail()))
        ->setBody($msg);

        $this->mailer->send($message);
    }
}
