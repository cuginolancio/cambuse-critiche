<?php

use Silex\Provider;
use Lancio\Cambuse\Provider\UserProvider;
use Lancio\Cambuse\Controller;
use Lancio\Cambuse\Repository;
use Lancio\Security\Authentication\Provider\JoomlaProvider;
use Lancio\Security\Firewall\JoomlaListener;
use Lancio\Security\Encoder\JoomlaPasswordEncoder;
//use Symfony\Component\EventDispatcher\EventDispatcher;
        
$app->register(new Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../logs/development.log',
));
$app->register(new Provider\DoctrineServiceProvider() );
$app->register(new Provider\SwiftmailerServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), [
    'locale_fallbacks' => ['it'],
]);
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Provider\ValidatorServiceProvider(), [
    'validator.validator_service_ids' => [
        'unique.product.validator' => 'unique.product.validator'
    ]
]);
$app->register(new Provider\FormServiceProvider());
$app->register(new Provider\TwigServiceProvider());
$app->register(new Provider\ServiceControllerServiceProvider());
$app->register(new Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login' => array(
            'pattern' => '^/login$',
        ),
        'admin' => array(
            'pattern' => '^/',
            'anonymous' => array(),
            'form' => array(
                'login_path' => "/login",
                'check_path' => "/login_check",
                "default_target_path" => "/",
                "always_use_default_target_path" => true,
                'username_parameter' => '_username',
                'password_parameter' => '_password',
//                "csrf_parameter" => "login[_token]",
                "failure_path" => "/login",
            ),
            'logout' => array(
                'logout_path' => "/logout",
                "target" => '/login',
                "invalidate_session" => true,
            ),
            'users' => $app->share(function () use ($app) {
		return $app['user_provider'];
            }),
        ),
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

$app->register(new Provider\SessionServiceProvider());

return $app;