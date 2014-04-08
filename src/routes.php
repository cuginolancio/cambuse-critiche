<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

$app->get("/", "site.controller:IndexAction")->bind('homepage');


$app->get("/login", "site.controller:LoginAction")->bind('login');

$app->get("/market", "market.controller:IndexAction")
        ->bind('market.farms');

$app->get("/pachamama", "pachamama.controller:IndexAction")
        ->bind('pachamama.catalog');
$app->post("/pachamama/order", "pachamama.controller:OrderAction")->bind('pachamama.order');
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

require __DIR__ . "/../resources/db/schema.php";

return $app;