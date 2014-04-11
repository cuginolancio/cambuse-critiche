<?php

use Silex\Provider;
use Lancio\Cambuse\Provider\UserProvider;
use Lancio\Cambuse\Controller;
use Lancio\Cambuse\Repository;
use Lancio\Security\Authentication\Provider\JoomlaProvider;
use Lancio\Security\Firewall\JoomlaListener;
use Lancio\Security\Encoder\JoomlaPasswordEncoder;
use Lancio\Cambuse\Event\OrderConfirmEvent;
use Symfony\Component\HttpFoundation\Response;  
use Lancio\Cambuse\Listener\OrderEmailListener;
      
$app['security.encoder.digest'] = $app->share(function () use ($app) {
    return new JoomlaPasswordEncoder();
});         

$app['user'] = $app->share(function() use ($app){
    
    $token = $app['security']->getToken();
    if ($token) {
        $user = $token->getUser(); 
    }
    
    if (!empty($user)) {
        return $user;
    }
    return null;
});

//repositories
$app['product.repository'] = $app->share(function() use ($app) {
   return new Repository\ProductRepository($app['dbs']['cambuse']); 
});

//providers
$app['user_provider'] = $app->share(function() use ($app) {
   return new UserProvider($app['dbs']['j']); 
});

//validators
$app['unique.product.validator'] = $app->share(function() use ($app) {
   return new \Lancio\Cambuse\Validator\Constraints\UniqueProductCodeInDbValidator($app['product.repository']); 
});

//controllers
$app['pachamama.controller'] = $app->share(function() use ($app) {
    return new Controller\PachamamaController($app, $app['product.repository']);
});
$app['market.controller'] = $app->share(function() use ($app) {
    return new Controller\MarketController($app, $app['product.repository']);
});
$app['site.controller'] = $app->share(function() use ($app) {
    return new Controller\SiteController($app);
});
$app['user.controller'] = $app->share(function() use ($app) {
    return new Controller\UserController($app);
});

$app['admin.controller'] = $app->share(function() use ($app) {
    return new Controller\AdminController($app, $app['product.repository']);
});


// Listener
$app['confirm.order.listener'] = $app->share(function() use ($app) {
    return new OrderEmailListener($app, $app['product.repository']);
});
//$app->boot();

require __DIR__ . "/../resources/db/schemaCambuse.php";
require __DIR__ . "/../resources/db/schemaJoomla.php";



$app['dispatcher']->addListener("confirm.order.event", function(OrderConfirmEvent $e ) use($app) {
    
    $app['confirm.order.listener']->sendToCustomer($e);
    $app['confirm.order.listener']->sendToBusiness($e);
});

return $app;