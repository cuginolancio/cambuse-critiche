<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get("/", "site.controller:IndexAction")
        ->bind('homepage');

$app->get("/login", "site.controller:LoginAction")
        ->bind('login');

$app->get("/market", "market.controller:IndexAction")
        ->bind('market.farms');


$app->match("/pachamama", "pachamama.controller:IndexAction")
        ->bind('pachamama.catalog')
        ->method("GET|POST");

$app->match("/pachamama/order", "pachamama.controller:OrderAction")
        ->bind('pachamama.order')
        ->method("GET|POST");

$app->match("/pachamama/confirm/{id}", "pachamama.controller:ConfirmAction")
        ->bind('pachamama.confirm')
        ->method("GET|POST");


$app->get("/user/orders", "user.controller:OrdersAction")
        ->bind('user.orders');




$app->get('/admin', 'admin.controller:IndexAction')
        ->bind('admin');

$app->get('/admin/pachamama/products', 'admin.pachamama.controller:ProductsAction')
        ->bind('admin.pachamama.products');
$app->patch('/admin/pachamama/products/{id}', 'admin.pachamama.controller:changeStatusAction')
        ->bind('admin.pachamama.product.status')
        ->assert("id","\d+");
$app->get('/admin/pachamama/products/{id}', 'admin.pachamama.controller:editAction')
        ->bind('admin.pachamama.product.edit')
        ->assert("id","\d+");
$app->put('/admin/pachamama/products/{id}', 'admin.pachamama.controller:updateAction')
        ->bind('admin.pachamama.product.update')
        ->assert("id","\d+");
$app->get('/admin/pachamama/products/new', 'admin.pachamama.controller:newAction')
        ->bind('admin.pachamama.product.new');
$app->post('/admin/pachamama/products', 'admin.pachamama.controller:createAction')
        ->bind('admin.pachamama.product.create');
$app->delete('/admin/pachamama/products/{id}', 'admin.pachamama.controller:deleteAction')
        ->bind('admin.pachamama.product.delete');

$app->get('/admin/markets/markets', 'admin.market.controller:MarketsAction')
        ->bind('admin.markets.markets');
$app->patch('/admin/pachamama/products/{id}', 'admin.market.controller:changeStatusAction')
        ->bind('admin.markets.market.status')
        ->assert("id","\d+");
$app->get('/admin/pachamama/products/{id}', 'admin.market.controller:editAction')
        ->bind('admin.markets.market.edit')
        ->assert("id","\d+");
$app->put('/admin/pachamama/products/{id}', 'admin.market.controller:updateAction')
        ->bind('admin.markets.market.update')
        ->assert("id","\d+");
$app->get('/admin/pachamama/products/new', 'admin.market.controller:newAction')
        ->bind('admin.markets.market.new');
$app->post('/admin/pachamama/products', 'admin.market.controller:createAction')
        ->bind('admin.markets.market.create');
$app->delete('/admin/pachamama/products/{id}', 'admin.market.controller:deleteAction')
        ->bind('admin.markets.market.delete');

//$app->get('/blog/{id}', function ($id) use ($app) {
//    $sql = "SELECT * FROM posts WHERE id = ?";
//    $post = $app['dbs']['mysql_read']->fetchAssoc($sql, array((int) $id));
//
//    $sql = "UPDATE posts SET value = ? WHERE id = ?";
//    $app['dbs']['mysql_write']->executeUpdate($sql, array('newValue', (int) $id));
//
//    return  "<h1>{$post['title']}</h1>".
//            "<p>{$post['body']}</p>";
//});

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});