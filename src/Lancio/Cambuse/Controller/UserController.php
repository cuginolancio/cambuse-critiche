<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Lancio\Cambuse\Repository\OrderRepository;

class UserController
{
    protected $app;

    public function __construct(Application $app, OrderRepository $repo)
    {
        $this->app = $app;
        $this->orderRepo = $repo;
    }

    public function ordersAction()
    {
        $user = $this->app['user'];
        $orders = $this->orderRepo->findAllByUserId($user->getId());
        return $this->app['twig']->render('User/orders.html.twig', array(
            'orders' => $orders,
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