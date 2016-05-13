<?php

namespace Proethos2\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProtocolControllerTest extends WebTestCase
{
    var $meeting_id = NULL;
    var $protocol_repository;
    var $submission_repository;
    var $meeting_repository;
    var $faq_repository;
    var $client;
    var $_em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->meeting_repository = $this->_em->getRepository('Proethos2ModelBundle:Meeting');
        $this->faq_repository = $this->_em->getRepository('Proethos2ModelBundle:Faq');
        $this->submission_repository = $this->_em->getRepository('Proethos2ModelBundle:Submission');
        $this->protocol_repository = $this->_em->getRepository('Proethos2ModelBundle:Protocol');
        
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));
    }

    public function testFirstStepGET()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();
    }
}
