<?php

use Silex\Provider;
//use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Lancio\Cambuse\Provider\UserProvider;
use Lancio\Cambuse\Controller;
use Lancio\Cambuse\Repository;
use Lancio\Security\Authentication\Provider\JoomlaProvider;
use Lancio\Security\Firewall\JoomlaListener;
use Lancio\Security\Encoder\JoomlaPasswordEncoder;

//load conf
switch($app['env']){
    case 'prod':
        require __DIR__ . "/../resources/config/" . "prod.php";
        break;
    case 'dev':
        require __DIR__ . "/../resources/config/" . "dev.php";
        break;
    case 'test':
        require __DIR__ . "/../resources/config/" . "test.php";
        break;
        
}
$app->register(new Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/development.log',
));

$app->register(new Provider\DoctrineServiceProvider() );

$app->register(new Provider\SwiftmailerServiceProvider());

$app->register(new Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('it'),
));
$app->register(new Provider\ServiceControllerServiceProvider());

$app->register(new Provider\UrlGeneratorServiceProvider());

$app->register(new Provider\ValidatorServiceProvider(), [
    'validator.validator_service_ids' => array(
        'unique.product.validator' => 'unique.product.validator'
    )
]);

$app->register(new Provider\FormServiceProvider());

$app->register(new Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../resources/views',
));
$app->register(new Provider\ServiceControllerServiceProvider());

$app->register(new Provider\SecurityServiceProvider(), array(
//    'security.encoders' => array(
//        'Lancio\Cambuse\Entity\User' => 'sha512'
//    ),
//    'security.providers' => array(
//        'main' => array(
//            'entity' => array(
//                'class'     => 'Lancio\Cambuse\Entity\User',
//                'property'  => 'username'
//            )
//        )
//    ),
    'security.firewalls' => array(
        'login' => array(
            'pattern' => '^/login$',
        ),
        'admin' => array(
            'pattern' => '^/',
//            'joomla' => true,
            'anonymous' => array(),
            'form' => array(
                'login_path' => "/login",
                'check_path' => "/login_check",
//                "default_target_path" => "/admin/user/profile",
//                "always_use_default_target_path" => true,
//                'username_parameter' => 'login[username]',
//                'password_parameter' => 'login[password]',
//                "csrf_parameter" => "login[_token]",
//                "failure_path" => "/user/login",
            ),
            'logout' => array(
                'logout_path' => "/logout",
                "target" => '/login',
                "invalidate_session" => true,
//                "delete_cookies" => array(
//                    "mongoblog.local" => array("domain" => "mongoblog.local", "path" => "/")
//                )
            ),
            'users' => $app->share(function () use ($app) {
		return $app['user_provider'];
            }),
//            'security' => $app['debug'] ? false : true,
        ),
    
//        'secured' => array(
//            'pattern' => '^/.*$',
//            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
//            'logout' => array('logout_path' => '/logout'),
//            'users' => $app->share(function () use ($app) {
//		return new UserProvider($app['dbs']['cambuse']);
//            }),
//        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'),
    ),
    'security.access_rules' => array(
        array('^/admin', 'ROLE_ADMIN'),
        array('^/.*$', 'ROLE_USER'),
        array('^/login$','IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/_profiler','IS_AUTHENTICATED_ANONYMOUSLY'),
    ),
));
            
$app['security.encoder.digest'] = $app->share(function ($app) {
    return new JoomlaPasswordEncoder();
});

//$app['security.authentication_listener.factory.joomla'] = $app->protect(function ($name, $options) use ($app) {
//    // define the authentication provider object
//    $app['security.authentication_provider.'.$name.'.joomla'] = $app->share(function () use ($app) {
//        return new JoomlaProvider($app['user_provider'], dirname(__DIR__).'/cache/security_cache');
//    });
//
//    // define the authentication listener object
//    $app['security.authentication_listener.'.$name.'.joomla'] = $app->share(function () use ($app) {
//        return new JoomlaListener($app['security'], $app['security.authentication_manager']);
//    });
//
//    return array(
//        // the authentication provider id
//        'security.authentication_provider.'.$name.'.joomla',
//        // the authentication listener id
//        'security.authentication_listener.'.$name.'.joomla',
//        // the entry point id
//        null,
//        // the position of the listener in the stack
//        'pre_auth'
//    );
//});
            
            
            
if("dev" === $app['env']){
    $app->register(new Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}

$app->register(new Provider\SessionServiceProvider(), [
//    'session.storage.save_path' => dirname(__DIR__) . '/cache/sessions'
]);


$app['user'] = $app->share(function() use ($app){
    
    $token = $app['security']->getToken();
    if($token)
        $user = $token->getUser(); 
    if(!empty($user))
        return $user;
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

$app->boot();

require __DIR__ . "/../resources/db/schema.php";

$app = require __DIR__ . "/../src/routes.php";

return $app;