<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Lancio\Cambuse\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Lancio\Cambuse\Event\OrderConfirmEvent;

class PachamamaController
{
    protected $repo;
    protected $app;

    public function __construct(Application $app, ProductRepository $repo)
    {
        $this->repo = $repo;
        $this->app = $app;
    }

    public function indexAction()
    {
        $products = $this->repo->findAllActive();
        $lastOrder = $this->app['session']->get('order_request', []);
        
        foreach ($products as &$product) {
            if (array_key_exists($product->getCode(), $lastOrder)) {
                $product->getQuantity($lastOrder[$product->getCode()]);
            }
        }

        return $this->app['twig']->render('Pachamama/index.html.twig', array(
            "products" => $products,
        ));
    }
    
    public function orderAction(Request $request)
    {
        $qty = $request->request->get('qty', []);
        
        $qtyProducts = array_filter($qty, function ($v) { return ($v+0)>0;});
        if (!is_array($qtyProducts) || !count($qtyProducts)) {
            
            $this->app['session']->getBag('flashes')->add("warning","Nessun prodotto selezionato");
            return $this->app->redirect($this->app['url_generator']->generate('pachamama.catalog'));
        }
        
        $products = $this->repo->findActiveByCodes(array_keys($qtyProducts));
        
        foreach ($products as $product) {
            $product->setQuantity($qtyProducts[$product->getCode()]);
        }
        
        $order = new \Lancio\Cambuse\Entity\Order($this->app['user'], $products);
        
        $action = $this->app['url_generator']->generate('pachamama.confirm');
        
        $this->app['session']->set('order_request', $qtyProducts);
        $this->app['session']->set('order', $products);
        
        $form = $this->createOrderForm($action, $qtyProducts);
        
        return $this->app['twig']->render('Pachamama/order.html.twig', array(
            "order" => $order,
            
            "form" => $form->createView(),
        ));
    }
    
    private function createOrderForm($action, $qtyProducts) 
    {
        $form = $this->app['form.factory']->createBuilder('form', ['data' => serialize($qtyProducts)])
            ->setAction($action)
            ->setMethod("POST")
            ->add('data', 'hidden', [
                'constraints' => [new Assert\NotBlank()]
            ])
            ->getForm();
        
        return $form;
    }
    
    public function confirmAction(Request $request)
    {
        $serializedProducts = $request->request->get('data', "");
        $products = unserialize($serializedProducts);
        $form = $this->createOrderForm("", []);
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            $values = $form->getData();
            $qtyProducts = unserialize($values['data']);

            $products = $this->repo->findActiveByCodes(array_keys($qtyProducts));
        
            foreach ($products as $product) {
                $product->setQuantity($qtyProducts[$product->getCode()]);
            }

            $order = new \Lancio\Cambuse\Entity\Order($this->app['user'], $products);

            $eventMail = new OrderConfirmEvent();
            $eventMail->setOrder($order);
            
            $this->app['dispatcher']->dispatch("confirm.order.event", $eventMail);
            
            $this->app['session']
                    ->getBag('flashes')
                    ->add("success","Ti abbiamo inviato una email con il riepilogo dell'ordine");
//            $this->app['session']->set('order_request', null);
//            $this->app['session']->set('order', null);
            return $this->app['twig']->render('Pachamama/confirm.html.twig', []);
        }
        return $this->app->redirect($this->app['url_generator']->generate('pachamama.order'));
        
    }
}