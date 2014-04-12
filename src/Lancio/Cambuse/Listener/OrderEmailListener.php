<?php

namespace Lancio\Cambuse\Listener;

use Silex\Application;
use Lancio\Cambuse\Event\OrderConfirmEvent;
use Lancio\Cambuse\Entity\User;
use Lancio\Cambuse\Entity\Order;
/**
 * Description of SendOrderEmailListener
 *
 * @author lancio
 */
class OrderEmailListener {
    
    public function __construct(Application $app, $debug = false )
    {
        $this->mailer = $app['mailer'];
        $this->debug = $debug;
        $this->shops = $app['shops'];
        $this->twig = $app['twig'];
    }
    
    public function sendEmail(OrderConfirmEvent $e )
    {
        $shop = $e->getShop();
        $order = $e->getOrder();
        $user = $order->getUser();
        
        $shopEmail = $this->debug ? "cuginolancio@gmail.com": $this->shops[$shop]['email'];
        $shopName = $this->shops[$shop]['name'];
        $to = $this->debug ? "cuginolancio@gmail.com": $user->getEmail();
        
//        $msg = $this->getMailText($order, $user);
        $msg = $this->getMailHtml($order, $user);
    
        $message = \Swift_Message::newInstance()
            ->setSubject('[CambuseCritiche] Ordine n' . $order->getId())
            ->setFrom(array($user->getEmail()))
            ->setTo(array($to))
            ->setBcc($shopEmail, $shopName)
            ->setBody($msg, "text/html");

        return $this->mailer->send($message);
    }
    
    protected function getMailText(Order $order, User $user)
    {
        $msgProduct = "";
        
        foreach ($order->getProducts() as $product) {
            $msgProduct .= " | " . $product->getCode() 
                    . " \t| ". " € ". $product->getPrice(true) 
                    . " \t| " . $product->getQuantity() 
                    . " \t| " . $product->getTotal(true) 
                    . " \t| ". $product->getName() 
                    . " \t| \n\t" ;
        }

        $msg = <<<MSG
    === Dati Utente ===  
        Nome:\t{$user->getName()} {$user->getSurname()}
        Gruppo:\t{$user->getScoutGroup()}
        Unità:\t{$user->getScoutUnit()}
        Email:\t{$user->getEmail()}
        Telefono: \t{$user->getPhone()}
        
    === Riepilogo Ordine === 
\t | cod. \t| prezzo(uni) \t| qt. \t| prezzo \t| prodotto \t| 
\t$msgProduct
\t_____________
\tTotale: € {$order->getTotal(true)}
\tNote: \n
    {$order->getNote()}
\t_____________

-- 
Email inviata automaticamente dal sistema. 
Cambuse Critiche 
www.agescirimini.it
MSG;

        return $msg;
    }
    
    protected function getMailHtml(Order $order, User $user)
    {
        $msg = $this->twig->render('Pachamama/email/confirm.html.twig', [
            'order' => $order,
        ]);
        
        return $msg;
    }
}
