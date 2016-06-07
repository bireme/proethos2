<?php

namespace Proethos2\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    var $client;
    var $_em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }

    public function login() {
        return $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));
    }

    public function testLogin()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router')->generate('login_route', array(), false);
        
        $client->request('GET', $route);
        $this->assertNotEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testChangePasswordGET()
    {
        $client = $this->login();
        
        $route = $client->getContainer()->get('router')->generate('security_change_password', array(), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testForgotMyPasswordGET()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router')->generate('security_forgot_my_password', array(), false);
        
        $client->request('GET', $route);
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testForgotMyPasswordPOST()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router')->generate('security_forgot_my_password', array(), false);
        
        $client->request('POST', $route, array("email" => "moa.moda@gmail.com"));
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testResetMyPasswordGET()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router')->generate('security_reset_my_password', array(), false);
        
        $client->request('GET', $route);
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testNewUserGET()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router')->generate('security_new_user', array(), false);
        
        $client->request('GET', $route);
        $this->assertNotEquals(200, $client->getResponse()->getStatusCode());
    }

}
