<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class MarketController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function indexAction()
    {
        
        return $this->app['twig']->render('Market/index.html.twig', array(
        ));
    }
    
}