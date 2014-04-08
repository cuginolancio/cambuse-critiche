<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Lancio\Cambuse\Repository\ProductRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

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
            if (array_key_exists($product['code'], $lastOrder)) {
                $product['qty'] = $lastOrder[$product['code']];
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
        foreach ($products as $k => $product) {
            $products[$k]['qty'] = $qtyProducts[$product['code']];
            $total += $products[$k]['qty'] * $products[$k]['price'];
        }
        
        $action = $this->app['url_generator']->generate('pachamama.confirm');
        
        $this->app['session']->set('order_request', $qtyProducts);
        $this->app['session']->set('order', $products);
        
        $form = $this->createOrderForm($action, $products);
        
        return $this->app['twig']->render('Pachamama/order.html.twig', array(
            "total" => $total,
            "products" => $products,
            "form" => $form->createView(),
        ));
    }
    private function createOrderForm($action, $products) {
        $form = $this->app['form.factory']->createBuilder('form', ['data' => serialize($products)])
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
//        var_dump($request->request->all());
        $products = $request->request->get('data', "");
        $products = unserialize($products);
        $form = $this->createOrderForm("", []);
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            $values = $form->getData();
            $data = unserialize($values['data']);

            $eventMail = new \Symfony\Component\EventDispatcher\Event();
            $eventMail
                    ->setUser($user)
                    ->setOrder($data);
            
            $this->app['session']->getBag('flashes')->add("success","Ti abbiamo inviato una email con il riepilogo dell'ordine");
//            $this->app['session']->set('order_request', null);
//            $this->app['session']->set('order', null);
            return $this->app['twig']->render('Pachamama/confirm.html.twig', []);
        }
        return $this->app->redirect($this->app['url_generator']->generate('pachamama.order'));
        
        
    }
}