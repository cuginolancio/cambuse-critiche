<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function ordersAction()
    {
        
        return $this->app['twig']->render('Site/orders.html.twig', array(
        ));
    }
    
    public function loginAction(Request $request)
    {
        return $this->app['twig']->render('Site/login.html.twig', array(
            'error'         => $this->app['security.last_error']($request),
            'last_username' => $this->app['session']->get('_security.last_username'),
        ));
    }
}