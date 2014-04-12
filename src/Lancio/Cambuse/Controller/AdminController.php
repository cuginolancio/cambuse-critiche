<?php

namespace Lancio\Cambuse\Controller;

use Silex\Application;

class AdminController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function indexAction()
    {
        return $this->app['twig']->render('Admin/index.html.twig', array(
        ));
    }
}