<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Lancio\Cambuse\Repository\ProductRepository;
use Lancio\Cambuse\Repository\OrderRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Lancio\Cambuse\Event\OrderConfirmEvent;
use Lancio\Cambuse\Entity\Order;

class PachamamaController
{
    protected $productRepo;
    protected $orderRepo;
    protected $app;

    public function __construct(Application $app, OrderRepository $orderRepo, ProductRepository $productRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->productRepo = $productRepo;
        $this->app = $app;
    }

    public function indexAction(Request $request)
    {
        if("POST" === $request->getMethod()) {
            
            $qty = $request->request->get('qty', []);
            $qtyProducts = array_filter($qty, function ($v) { return ($v+0)>0;});
            
            if (is_array($qtyProducts) && count($qtyProducts)) {
            
                $this->app['session']->set('order_request', $qtyProducts);
                return $this->app->redirect($this->app['url_generator']->generate('pachamama.order'));
            }
            
            $this->app['session']->getBag('flashes')->add("warning","Nessun prodotto selezionato");
        } 
            
        $qtyProducts = $this->app['session']->get('order_request', []);
        
        $products = $this->productRepo->findAllActive();
        
        foreach ($products as &$product) {
            if (array_key_exists($product->getCode(), $qtyProducts)) {
                $product->getQuantity($qtyProducts[$product->getCode()]);
            }
        }

        return $this->app['twig']->render('Pachamama/index.html.twig', array(
            "products" => $products,
        ));
    }
    
    public function orderAction(Request $request)
    {
        $qtyProducts = $this->app['session']->get('order_request');

        if (!is_array($qtyProducts) || !count($qtyProducts)) {
            return $this->app->redirect($this->app['url_generator']->generate('pachamama.catalog'));
        }
        
        $products = $this->productRepo->findActiveByCodes(array_keys($qtyProducts));

        foreach ($products as $product) {
            $product->setQuantity($qtyProducts[$product->getCode()]);
        }

        $order = new Order('pachamama', $this->app['user'], $products);

        $action = $this->app['url_generator']->generate('pachamama.order');
        
        $form = $this->createOrderForm($action, $qtyProducts);
        $form->handleRequest($request);
        
        if("POST" === $request->getMethod() && $form->isValid()) {
            $values = $form->getData();
            $qtyProducts = unserialize($values['data']);
            $note = ($values['note']);
            $accept = ($values['accept']);

            $products = $this->productRepo->findActiveByCodes(array_keys($qtyProducts));

            foreach ($products as $product) {
                $product->setQuantity($qtyProducts[$product->getCode()]);
            }

            $order = new Order('pachamama', $this->app['user'], $products);
            $order->setNote($note)
                    ->setDatetime(new \DateTime())
                    ->setAccept($accept);
            
            $id = $this->orderRepo->save($order);

            $eventMail = new OrderConfirmEvent();
            $eventMail->setOrder($order);
            $eventMail->setShop("pachamama");

            $this->app['dispatcher']->dispatch("confirm.order.event", $eventMail);

            $this->app['session']
                    ->getBag('flashes')
                    ->add("success","Ti abbiamo inviato una email con il riepilogo dell'ordine");

            $this->app['session']->remove('order_request');
            $this->app['session']->remove('order');
                
            return $this->app->redirect($this->app['url_generator']->generate('pachamama.confirm', ['id' => $order->getId()]));
        }
        
        return $this->app['twig']->render('Pachamama/order.html.twig', array(
            "order" => $order,
            "form" => $form->createView(),
        ));
    }
    
    
    public function confirmAction(Request $request, $id)
    {
        $order = $this->orderRepo->find($id);
                
        if ($this->app['user']->getId() != $order->getUser()->getId()) {
            return $this->app->abort(404);
        }
        
        return $this->app['twig']->render('Pachamama/confirm.html.twig', [
            'order' => $order,
        ]);
    }
    
    private function createOrderForm($action, $qtyProducts) 
    {
        $form = $this->app['form.factory']->createBuilder('form', ['data' => serialize($qtyProducts)])
            ->setAction($action)
            ->setMethod("POST")
            ->add('data', 'hidden', [
                'constraints' => [new Assert\NotBlank()]
            ])
            ->add('note', 'textarea', [
                'label' => 'Note',
                'attr' => ['style' => 'width:100%; height:80px;'],
                'constraints' => [new Assert\NotBlank()]
            ])
                ->add('accept', 'checkbox', array(
                'label' => 'Accetto le condizioni',
                'constraints' => [new Assert\NotBlank()]
            ))
            ->getForm();
        
        return $form;
    }
    
}