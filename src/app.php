<?php

use Silex\Provider;
//use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Lancio\Cambuse\Provider\UserProvider;
use Lancio\Cambuse\Controller;
use Lancio\Cambuse\Repository;

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

$app->register(new Provider\DoctrineServiceProvider());

$app->register(new Provider\SwiftmailerServiceProvider());

$app->register(new Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('it'),
));
$app->register(new Provider\ServiceControllerServiceProvider());

$app->register(new Provider\UrlGeneratorServiceProvider());

$app->register(new Provider\SessionServiceProvider());

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

//$app['myapp.encoder.base64'] = new Base64PasswordEncoder();
//$app['security.encoder_factory'] = $app->share(function ($app) {
//    return new EncoderFactory(
//        array(
//            'Symfony\Component\Security\Core\User\UserInterface' => $app['security.encoder.digest'],
//            'MyApp\Model\UserInterface'                          => $app['myapp.encoder.base64'],
//        )
//    );
//});

$app->register(new Provider\SecurityServiceProvider(), array(
//    'security.encoders' => array(
//        'Lancio\Cambuse\Entity\User' => 'sha512'
//    ),
//    'security.providers' => array(
//        'main' => array(
//            'entity' => array(
//                'class'     => 'MyProject\Entity\User',
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
            "anonymous" => array(),
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
		return new UserProvider($app['db']);
            }),
        ),
    
//        'secured' => array(
//            'pattern' => '^/.*$',
//            'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
//            'logout' => array('logout_path' => '/logout'),
//            'users' => $app->share(function () use ($app) {
//		return new UserProvider($app['db']);
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
            
if("dev" === $app['env']){
    $app->register(new Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => __DIR__.'/../cache/profiler',
        'profiler.mount_prefix' => '/_profiler', // this is the default
    ));
}

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
   return new Repository\ProductRepository($app['db']); 
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