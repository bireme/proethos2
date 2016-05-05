<?php

namespace Proethos2\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Doctrine\ORM\EntityRepository;


class CRUDControllerTest extends WebTestCase
{
    var $meeting_id = NULL;
    var $meeting_repository;
    var $client;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->meeting_repository = $this->_em->getRepository('Proethos2ModelBundle:Meeting');
        
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));
    }

    public function testListMeetingGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_list', array(), false);
        
        $client->request('GET', $route);
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testListMeetingPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_list', array(), false);
        
        $client->request('POST', $route, array(
            'new-meeting-date' => "2016-05-22", 
            'new-meeting-subject' => "Assunto", 
            'new-meeting-content' => "Conteú\ndo",
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateMeetingGET()
    {   
        // getting last id
        $last_meeting = end($this->meeting_repository->findAll());
        $this->meeting_id = $last_meeting->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_update', array("meeting_id" => $this->meeting_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateMeetingPOST()
    {   
        // getting last id
        $last_meeting = end($this->meeting_repository->findAll());
        $this->meeting_id = $last_meeting->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_update', array("meeting_id" => $this->meeting_id), false);
        
        $client->request('POST', $route, array(
            'new-meeting-date' => "2016-05-28", 
            'new-meeting-subject' => "Assuntoaa", 
            'new-meeting-content' => "Conteú\ndoaa",
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testDeleteMeetingGET()
    {   
        // getting last id
        $last_meeting = end($this->meeting_repository->findAll());
        $this->meeting_id = $last_meeting->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_delete', array("meeting_id" => $this->meeting_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteMeetingPOST()
    {   
        // getting last id
        $last_meeting = end($this->meeting_repository->findAll());
        $this->meeting_id = $last_meeting->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_delete', array("meeting_id" => $this->meeting_id), false);
        
        $client->request('POST', $route, array(
            'meeting-delete' => "true", 
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }


}
