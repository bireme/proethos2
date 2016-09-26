<?php

// This file is part of the ProEthos Software.
//
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software.
//
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details.
//
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://github.com/bireme/proethos2/blob/master/LICENSE.txt


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
        $this->user_repository = $this->_em->getRepository('Proethos2ModelBundle:User');
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
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testForgotMyPasswordPOST()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router')->generate('security_forgot_my_password', array(), false);

        $client->request('POST', $route, array("email" => "admin@proethos2.com"));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testResetMyPasswordGET()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router')->generate('security_reset_my_password', array(), false);

        $user = $this->user_repository->findOneByEmail('admin@proethos2.com');

        $client->request('GET', $route, array('hashcode' => $user->getHashcode()));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNewUserGET()
    {
        $client = static::createClient();
        $route = $client->getContainer()->get('router')->generate('security_new_user', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

}
