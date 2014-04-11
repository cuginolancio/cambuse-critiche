<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class CambuseTest extends \Silex\WebTestCase
{
    public function createApplication() 
    {
        
        $app = new Silex\Application(["debug" => true, "env" => 'test'] );

        Request::enableHttpMethodParameterOverride();

        $app = require __DIR__ . "/../../src/app.php";

        require __DIR__ . "/../../config/{$app['env']}.php";

        require __DIR__ . "/../../src/shares.php";
        require __DIR__ . "/../../src/routes.php";

        
//        $app = new Application(["debug" => true, "env" => 'test']);
        
//        $app = require __DIR__.'/../../src/app.php';
        
        $app['exception_handler']->disable();
        
//        $app['session.test'] = true;
        
        return $app;
    }
    
    public function testInitialPageRedirectToLoginNotAuthenticated()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertCount(1, $client->getCrawler()->filter('h1:contains("Login")'));
    }

    public function testLoginPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());
        
    }
    
    public function testAdminPageNotAuthenticated()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin');

        $this->assertTrue($client->getResponse()->isRedirect());
        $client->followRedirect();

        $this->assertCount(1, $client->getCrawler()->filter('h1:contains("Login")'));
    }
    
    protected function authenticateUser()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form();

        $form['_username'] = 'lancio';
        $form['_password'] = 'lancio';

        $client->submit($form);
        
        return $client;
    }
    
    public function testLoginFailed()
    {
        $client = $this->authenticateUser();
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $client = $this->createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Login')->form(array());
        $crawler = $client->submit($form, array());

        $this->assertEquals(1, $crawler->filter('.alert-danger')->count());

        $form = $crawler->selectButton('Login')->form();
        $crawler = $client->submit($form, array(
            '_username' => 'wrong username',
            '_password' => 'wrong password',
        ));
        
        $this->assertEquals(1, $crawler->filter('.alert-danger')->count());
    }
    
    public function testLogin()
    {
        $client = $this->authenticateUser();
        $client->followRedirect();
        $crawler = $client->getCrawler();

        $this->assertEquals(1, $crawler->filter('a[href="/logout"]')->count());
    }
}
