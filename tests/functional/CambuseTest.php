<?php

use Silex\Application;

class CambuseTest extends \Silex\WebTestCase
{
    public function createApplication() 
    {
        $app = new Application(["debug" => true, "env" => 'test']);
        
        $app = require __DIR__.'/../../src/app.php';
        
        $app['exception_handler']->disable();
        $app['session.test'] = true;
//        $app->run();
        return $app;
    }
    
    public function testLogin()
    {
        $client = $this->createClient();
        $client->followRedirects(true);

        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());

        $form = $crawler->selectButton('Login')->form(array());
        $crawler = $client->submit($form, array());
        $this->assertEquals(1, $crawler->filter('.alert-error')->count());

        $form = $crawler->selectButton('Login')->form();
        $crawler = $client->submit($form, array(
            '_username' => 'wrong username',
            '_password' => 'wrong password',
        ));
        
        $this->assertEquals(1, $crawler->filter('.alert-error')->count());

        $form = $crawler->selectButton('Login')->form();
        $crawler = $client->submit($form, array(
            '_username' => 'admin',
            '_password' => 'test',
        ));
//        echo $client->getResponse()->getContent();
//        $this->assertEquals(2, $crawler->filter('a[href="/logout"]')->count());
    }

    public function testInitialPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect());
//        $this->assertCount(1, $crawler->filter('h1:contains("Contact us")'));
//        $this->assertCount(1, $crawler->filter('form'));
        
    }

    public function testLoginPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertTrue($client->getResponse()->isOk());
//        $this->assertCount(1, $crawler->filter('h1:contains("Contact us")'));
//        $this->assertCount(1, $crawler->filter('form'));
        
    }
    
    public function testAdminPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin');

        $this->assertTrue($client->getResponse()->isRedirect());

//        $this->assertCount(1, $crawler->filter('h1:contains("Contact us")'));
//        $this->assertCount(1, $crawler->filter('form'));
        
    }
}
