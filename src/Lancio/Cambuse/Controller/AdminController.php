<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Lancio\Cambuse\Repository\ProductRepository;
use Lancio\Cambuse\Exception\InvalidFormException;

class AdminController
{
    protected $app;
    protected $repo;

    public function __construct(Application $app, ProductRepository $repo)
    {
        $this->app = $app;
        $this->repo = $repo;
    }

    public function indexAction()
    {
        
        return $this->app['twig']->render('Admin/index.html.twig', array(
        ));
    }
    
    public function productsAction(Request $request)
    {
        $products = $this->repo->findAll();
        return $this->app['twig']->render('Admin/products.html.twig', array(
            'products' => $products
        ));
    }
    
    public function editAction(Request $request, $id)
    {
        $method = "PUT"; 
        $action = $this->app['url_generator']->generate('admin.product.update', array("id" => $id));
        $product = $this->repo->find($id);
        
        $form = $this->createForm($product, $method, $action);
        
        return $this->app['twig']->render('Admin/product_edit.html.twig', array(
            'product' => $product,
            'form' => $form->createView()
        ));
    }
    
    
    
    public function updateAction(Request $request, $id)
    {
        $method = "PUT"; 
        $action = $this->app['url_generator']->generate('admin.product.update', array("id" => $id));
        $product = $this->repo->find($id);
        
        try{
            $product = $this->processForm($product, $request, $method, $action);
            return $this->app->redirect($this->app['url_generator']->generate('admin.products'));
            
        }  catch (\Lancio\Cambuse\Exception\InvalidFormException $e){
            
            $form = $e->getForm();
        }   
        return $this->app['twig']->render('Admin/product_edit.html.twig', array(
            'product' => $product,
            'form' => $form
        ));
    }
    
    public function newAction(Request $request )
    {
        $method = "POST"; 
        $action = $this->app['url_generator']->generate('admin.product.create');
        $product = array(
//            'id' => '',
//            'code' => '',
//            'name' => '',
//            'price' => '',
//            'price_regular' => '',
//            'active' => '',
//            'category_id' => '',
        );
        $form = $this->createForm($product, $method, $action);
        
        return $this->app['twig']->render('Admin/product_new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function createAction(Request $request )
    {
        $method = "POST"; 
        $action = $this->app['url_generator']->generate('admin.product.create');
        $product = array();
        
        try{
            $product = $this->processForm($product, $request, $method, $action);
            
            return $this->app->redirect($this->app['url_generator']->generate('admin.products'));
            
        }  catch (InvalidFormException $e){
            
            $form = $e->getForm();
        }   
        
        return $this->app['twig']->render('Admin/product_new.html.twig', array(
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
            
            $this->repo->save($data);
            
            return $data;
        }

        throw new InvalidFormException("Invalid Form", $form);
    }
    
    private function createForm($product, $method, $action)
    {
        $form = $this->app['form.factory']->createBuilder('form', $product)
            ->setAction($action)
            ->setMethod($method)
            ->add('code', 'text', array(
                'label' => 'Codice',
                'constraints' => array(
                    new Assert\NotBlank(), 
                    new Assert\Length(array('min' => 2)),
                    new \Lancio\Cambuse\Validator\Constraints\UniqueProductCodeInDb()
                )
            ))
            ->add('name', 'text', array(
                'label' => 'Nome',
                'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
            ))
            ->add('price', 'text', array(
                'label' => 'Prezzo',
                'constraints' => array(new Assert\NotBlank(), new Assert\Range(array('min' => 0.01)))
            ))
            ->add('price_regular', 'text', array(
                'label' => 'Prezzo Riservato',
                'constraints' => array(new Assert\NotBlank(), new Assert\Range(array('min' => 0.01)))
            ))
            ->add('pieces_in_package', 'text', array(
                'label' => 'Pezzi nella confezione',
                'constraints' => array(new Assert\NotBlank(), new Assert\Range(array('min' => 1)))
            ))
            ->add('bio', 'choice', array(
                'label' => 'Biologico',
                'choices' => array(0 => 'NO', 1 => 'SI'),
                'expanded' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Choice(array(0,1))),
            ))
            ->add('active', 'choice', array(
                'label' => 'Stato',
                'choices' => array(0 => 'Non visibile', 1 => 'visibile'),
                'expanded' => true,
                'constraints' => array(new Assert\NotBlank(), new Assert\Choice(array(0,1))),
            ))
            ->add('category_id', 'choice', array(
                'label' => 'Categoria',
                'choices' => $this->repo->findCategories(),
                'expanded' => false,
                'constraints' => array(new Assert\NotBlank(), new Assert\Choice(array_keys($this->repo->findCategories()))),
            ))
            ->getForm();
        
        return $form;
    }
}