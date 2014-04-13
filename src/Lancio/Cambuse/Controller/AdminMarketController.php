<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Lancio\Cambuse\Repository\ProductRepository;
use Lancio\Cambuse\Repository\MarketRepository;
use Lancio\Cambuse\Exception\InvalidFormException;

class AdminMarketController
{
    protected $app;
    protected $marketRepo;

    public function __construct(Application $app, MarketRepository $marketRepo)
    {
        $this->app = $app;
        $this->marketRepo = $marketRepo;
    }

    public function editAction(Request $request, $id)
    {
        $method = "PUT"; 
        $action = $this->app['url_generator']->generate('admin.markets.market.update', array("id" => $id));
        $product = $this->marketRepo->find($id);
        
        $form = $this->createForm($product, $method, $action);
        
        return $this->app['twig']->render('Admin/market/market_edit.html.twig', array(
            'product' => $product,
            'form' => $form->createView()
        ));
    }
    
    public function updateAction(Request $request, $id)
    {
        $method = "PUT"; 
        $action = $this->app['url_generator']->generate('admin.markets.market.update', array("id" => $id));
        $product = $this->marketRepo->find($id);
        
        try{
            $product = $this->processForm($product, $request, $method, $action);
            return $this->app->redirect($this->app['url_generator']->generate('admin.markets.markets'));
            
        }  catch (\Lancio\Cambuse\Exception\InvalidFormException $e){
            
            $form = $e->getForm();
        }   
        return $this->app['twig']->render('Admin/market/market_edit.html.twig', array(
            'product' => $product,
            'form' => $form->createView()
        ));
    }
    
    public function newAction(Request $request )
    {
        $method = "POST"; 
        $action = $this->app['url_generator']->generate('admin.markets.market.create');
        $product = [];
        
        $form = $this->createForm($product, $method, $action);
        
        return $this->app['twig']->render('Admin/market/market_new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function createAction(Request $request )
    {
        $method = "POST"; 
        $action = $this->app['url_generator']->generate('admin.markets.market.create');
        $product = [];
        
        try{
            $product = $this->processForm($product, $request, $method, $action);
            
            return $this->app->redirect($this->app['url_generator']->generate('admin.markets.markets'));
            
        }  catch (InvalidFormException $e){
            
            $form = $e->getForm();
        }   
        
        return $this->app['twig']->render('Admin/market/market_new.html.twig', array(
            'product' => $product,
            'form' => $form->createView()
        ));
    }
    
    private function processForm($product, Request $request, $method, $action)
    {
        $form = $this->createForm($product, $method, $action);
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            
            $this->marketRepo->save($data);
            
            return $data;
        }

        throw new InvalidFormException("Invalid Form", $form);
    }
    
    private function createForm($product, $method, $action)
    {
        $code = !empty($product['code']) ? $product['code'] : null; 
        
        $form = $this->app['form.factory']->createBuilder('form', $product)
            ->setAction($action)
            ->setMethod($method)
            ->add('code', 'text', array(
                'label' => 'Codice',
                'constraints' => array(
                    new Assert\NotBlank(), 
                    new Assert\Length(array('min' => 2)),
                    new \Lancio\Cambuse\Validator\Constraints\UniqueProductCodeInDb(['code' =>$code])
                )
            ))
            ->add('name', 'text', array(
                'label' => 'Nome',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->add('city', 'text', array(
                'label' => 'Comune',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 2)))
            ))
            ->add('address', 'text', array(
                'label' => 'Indirizzo',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 2)))
            ))
            ->add('description', 'textarea', array(
                'label' => 'Descrizione',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 2, 'max' => 500)))
            ))
//            ->add('bio', 'choice', array(
//                'label' => 'Biologico',
//                'choices' => array(0 => 'NO', 1 => 'SI'),
//                'expanded' => true,
//                'constraints' => array(new Assert\NotBlank(), new Assert\Choice(array(0,1))),
//            ))
            ->add('phone', 'text', array(
                'label' => 'Telefono',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 2)))
            ))
            ->add('mobile', 'text', array(
                'label' => 'Cellulare',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 2)))
            ))
            ->add('email', 'email', array(
                'label' => 'Email',
                'constraints' => array(new Assert\NotBlank(), new Assert\Email, new Assert\Length(array('min' => 2)))
            ))
            ->add('link', 'text', array(
                'label' => 'Collegamento',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 2)))
            ))
            ->add('site', 'text', array(
                'label' => 'Sito',
                'constraints' => array(new Assert\NotBlank(), new Assert\Url, new Assert\Length(array('min' => 2)))
            ))
            ->add('active', 'choice', array(
                'label' => 'Stato',
                'choices' => array(0 => 'Non visibile', 1 => 'visibile'),
                'expanded' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Choice(array(0,1))),
            ))
            ->getForm();
        
        return $form;
    }
    
    public function changeStatusAction($id, $status) 
    {
        $active = ("enable" == strtolower($status)) ? true : false;
        
        $ok = $this->marketRepo->update(['active' => $active], $id);
        
        return new \Symfony\Component\HttpFoundation\JsonResponse(['response' => $ok]);
    }
    
    public function marketsAction() 
    {
        $markets = $this->marketRepo->findAll();
        
        return $this->app['twig']->render('Admin/market/markets.html.twig', array(
            'markets' => $markets
        ));
    }
}