<?php

namespace Proethos2\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Doctrine\ORM\EntityRepository;


class CRUDControllerTest extends WebTestCase
{
    var $meeting_id = NULL;
    var $protocol_repository;
    var $submission_repository;
    var $meeting_repository;
    var $faq_repository;
    var $document_repository;
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
        $this->document_repository = $this->_em->getRepository('Proethos2ModelBundle:Document');
        
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));
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

    public function testListMeetingGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_list', array(), false);
        
        $client->request('GET', $route);
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowMeetingGET()
    {   
        // getting last id
        $last_meeting = end($this->meeting_repository->findAll());
        $this->meeting_id = $last_meeting->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_show', array("meeting_id" => $this->meeting_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
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

    public function testListCommitteeProtocolGET()
    {   
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_protocol_list', array(), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListInvestigatorProtocolGET()
    {   
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_investigator_protocol_list', array(), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // getting last id
        $last_submission = end($this->submission_repository->findAll());
        $submission_id = $last_submission->getId();

        $this->_em->remove($last_submission->getProtocol());
        $this->_em->flush();
    }
    
    public function testListFaqPOST()
    {   
        // getting last id
        $last_question = end($this->faq_repository->findAll());
        $this->faq_id = $last_question->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_faq_list', array(), false);

        $client->request('POST', $route, array(
            'new-question' => "Teste de questão?", 
            'new-question-answer' => "Resposta", 
            'new-question-status' => "true",             
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testListFaqGET()
    {   
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_faq_list', array(), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateFaqGET()
    {   
        // getting last id
        $last_question = end($this->faq_repository->findAll());
        $this->faq_id = $last_question->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_faq_update', array("faq_id" => $this->faq_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateFaqPOST()
    {   
        // getting last id
        $last_question = end($this->faq_repository->findAll());
        $this->faq_id = $last_question->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_faq_update', array("faq_id" => $this->faq_id), false);

        $client->request('POST', $route, array(
            'new-question' => "Teste de questão?", 
            'new-question-answer' => "Resposta", 
            'new-question-status' => "true",             
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testFaqGET()
    {   
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_faq_list', array(), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testDeleteFaqGET()
    {   
        // getting last id
        $last_question = end($this->faq_repository->findAll());
        $this->faq_id = $last_question->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_faq_delete', array("faq_id" => $this->faq_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteFaqPOST()
    {   
        // getting last id
        $last_question = end($this->faq_repository->findAll());
        $this->faq_id = $last_question->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_faq_delete', array("faq_id" => $this->faq_id), false);

        $client->request('POST', $route, array(
            'question-delete' => "true", 
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testListCommitteeDocumentGET()
    {   
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_list', array(), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListCommitteeDocumentPOST()
    {   
        // getting last id
        $last_question = end($this->faq_repository->findAll());
        $this->faq_id = $last_question->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_list', array(), false);

        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new UploadedFile(
            $file,
            'new_image.png'
        );
        
        $client->request('POST', $route, array(
            'title' => "Teste de Documento", 
            'description' => "Descrição do documento", 
            'role' => 2, 
            'status' => true, 
        ), array('file' => $image, ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateCommitteeDocumentGET()
    {   
        // getting last id
        $last_document = end($this->document_repository->findAll());
        $this->document_id = $last_document->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_update', array("document_id" => $this->document_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateCommitteeDocumentPOST()
    {   
        // getting last id
        $last_document = end($this->document_repository->findAll());
        $this->document_id = $last_document->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_update', array("document_id" => $this->document_id), false);

        $client->request('POST', $route, array(
            'title' => "Teste de Documento2233", 
            'description' => "Descrição do documento", 
            'role' => 2, 
            'status' => true,          
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testDeleteCommitteeDocumentGET()
    {   
        // getting last id
        $last_document = end($this->document_repository->findAll());
        $this->document_id = $last_document->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_delete', array("document_id" => $this->document_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteCommitteeDocumentPOST()
    {   
        // getting last id
        $last_document = end($this->document_repository->findAll());
        $this->document_id = $last_document->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_delete', array("document_id" => $this->document_id), false);

        $client->request('POST', $route, array(
            'delete' => "true", 
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }
}
