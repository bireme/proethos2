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
use Symfony\Component\HttpFoundation\File\UploadedFile;


class MonitoringControllerTest extends WebTestCase
{
    var $protocol_repository;
    var $client;
    var $_em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->protocol_repository = $this->_em->getRepository('Proethos2ModelBundle:Protocol');

        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));
    }

    public function testNewMonitoringGET()
    {
        // getting last id
        $all_protocols = $this->protocol_repository->findAll();
        $last_protocol = end($all_protocols);
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_new_monitoring', array("protocol_id" => $protocol_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testMonitoringCreateActionPOST()
    {
        // getting last id
        $all_protocols = $this->protocol_repository->findAll();
        $last_protocol = end($all_protocols);
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_new_monitoring', array("protocol_id" => $protocol_id), false);

        $client->request('POST', $route, array(
            'monitoring-action' => '1',
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testMonitoringCreateThatNotAmendmentGET()
    {
        // getting last id
        $all_protocols = $this->protocol_repository->findAll();
        $last_protocol = end($all_protocols);
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate(
            'protocol_new_monitoring_that_not_amendment',
            array("protocol_id" => $protocol_id, 'monitoring_action_id' => 2),
            false
        );

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testMonitoringCreateThatNotAmendmentPOST()
    {
        // getting last id
        $all_protocols = $this->protocol_repository->findAll();
        $last_protocol = end($all_protocols);
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate(
            'protocol_new_monitoring_that_not_amendment',
            array("protocol_id" => $protocol_id, 'monitoring_action_id' => 2),
            false
        );

        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new UploadedFile(
            $file,
            'new_image.png'
        );


        $client->request('POST', $route, array(
            'monitoring-action' => '2',
            'justification' => 'teste de justification',
            "new-atachment-file" => $image,
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }
}
