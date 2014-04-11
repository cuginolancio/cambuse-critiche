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


$app->get("/pachamama", "pachamama.controller:IndexAction")
        ->bind('pachamama.catalog');

$app->post("/pachamama/order", "pachamama.controller:OrderAction")
        ->bind('pachamama.order');

$app->match("/pachamama/confirm", "pachamama.controller:ConfirmAction")
        ->bind('pachamama.confirm')
        ->method("GET|POST");


$app->get("/user/orders", "user.controller:OrdersAction")
        ->bind('user.orders');




$app->get('/admin', 'admin.controller:IndexAction')
        ->bind('admin');
$app->get('/admin/products', 'admin.controller:ProductsAction')
        ->bind('admin.products');
$app->patch('/admin/products/{id}', 'admin.controller:changeStatusAction')
        ->bind('admin.product.status')
        ->assert("id","\d+");
$app->get('/admin/products/{id}', 'admin.controller:editAction')
        ->bind('admin.product.edit')
        ->assert("id","\d+");
$app->put('/admin/products/{id}', 'admin.controller:updateAction')
        ->bind('admin.product.update')
        ->assert("id","\d+");
$app->get('/admin/products/new', 'admin.controller:newAction')
        ->bind('admin.product.new');
$app->post('/admin/products', 'admin.controller:createAction')
        ->bind('admin.product.create');
$app->delete('/admin/products/{id}', 'admin.controller:deleteAction')
        ->bind('admin.product.delete');

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