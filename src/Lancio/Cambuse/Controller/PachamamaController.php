<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Lancio\Cambuse\Repository\ProductRepository;

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

    public function IndexAction()
    {
        $products = $this->repo->findAllActive();
        return $this->app['twig']->render('Pachamama/index.html.twig', array(
            "products" => $products,
        ));
    }
    
    public function OrderAction(Request $request)
    {
        $qtyProducts = $request->request->get('qty', []);
        
        $qtyProducts = array_filter($qtyProducts, function($v){ return ($v+0)>0;});
        if (!is_array($qtyProducts) || !count($qtyProducts)) {
            
            $this->app['session']->getBag('flashes')->add("warning","Nessun prodotto selezionato");
            return $this->app->redirect($this->app['url_generator']->generate('pachamama.catalog'));
        }
        
        $products = $this->repo->findActiveByCodes(array_keys($qtyProducts));
        foreach($products as $k => $product){
            $products[$k]['qty'] = $qtyProducts[$product['code']];
        }
        $action = $this->app['url_generator']->generate('pachamama.confirm');
        $method = "POST";
        
        $this->app['session']->set('order', $products);
        
        $form = $this->app['form.factory']->createBuilder('form', ['data' => $products])
            ->setAction($action)
            ->setMethod($method)
            ->add('data', 'hidden')
            ->getForm();
        
        
        return $this->app['twig']->render('Pachamama/order.html.twig', array(
            "products" => $products,
            "form" => $form->createView(),
        ));
    }
}