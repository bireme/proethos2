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
        $this->protocol_revision_repository = $this->_em->getRepository('Proethos2ModelBundle:ProtocolRevision');
        
        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));
    }

    public function testShowProtocolGET()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_show_protocol', array("protocol_id" => $protocol_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAnalyseProtocolGET()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_analyse_protocol', array("protocol_id" => $protocol_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAnalyseProtocolPOST()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_analyse_protocol', array("protocol_id" => $protocol_id), false);

        $post_data = array(
            "is-reject" => "false",
            "send-to" => "comittee",
            "reject-reason" => "",
            "action" => "accept",
        );
        
        $client->request('POST', $route, $post_data);
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeScreeningGET()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_screening', array("protocol_id" => $protocol_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeScreeningPOST()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_screening', array("protocol_id" => $protocol_id), false);

        $post_data = array(
            "send-to" => "ethical-revision",
            "committee-screening" => "teste",
        );
        
        $client->request('POST', $route, $post_data);
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeReviewGET()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_review', array("protocol_id" => $protocol_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeReviewPOST()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_review', array("protocol_id" => $protocol_id), false);

        $post_data = array(
            "send-to" => "",
            "opinion-required" => "1",
            "meeting" => "",
        );
        
        $client->request('POST', $route, $post_data);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeReviewPOST2()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_review', array("protocol_id" => $protocol_id), false);

        $post_data = array(
            "type-of-members" => "members-of-committee",
            "select-members-of-committee" => array("1"),
        );
        
        $client->request('POST', $route, $post_data);
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeReviewRevisorGET()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_review_revisor', array("protocol_id" => $protocol_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeReviewRevisorPOST()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_review_revisor', array("protocol_id" => $protocol_id), false);

        $post_data = array(
            "is-final-revision" => "false",
            "decision" => "Decision",
            "social-value" => "Social Value",
            "scientific-validity" => "Scientific Validity",
            "fair-participant-selection" => "Fair participant selection",
            "favorable-balance" => "Favorable balance of benefits and risks",
            "informed-consent" => "Informed Consent",
            "respect-for-participants" => "Respect for participants",
            "other-comments" => "Other Comments",
            "sugestions" => "Sugestions",
        );
        
        $client->request('POST', $route, $post_data);
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeReviewRevisorPOST2()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_review_revisor', array("protocol_id" => $protocol_id), false);

        $post_data = array(
            "is-final-revision" => "true",
            "decision" => "Decision",
            "social-value" => "Social Value",
            "scientific-validity" => "Scientific Validity",
            "fair-participant-selection" => "Fair participant selection",
            "favorable-balance" => "Favorable balance of benefits and risks",
            "informed-consent" => "Informed Consent",
            "respect-for-participants" => "Respect for participants",
            "other-comments" => "Other Comments",
            "sugestions" => "Sugestions",
        );
        
        $client->request('POST', $route, $post_data);
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testInitCommitteeReviewPOST3()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_review', array("protocol_id" => $protocol_id), false);

        $post_data = array(
            "send-to" => "",
            "opinion-required" => "1",
            "meeting" => "2",
        );
        
        $client->request('POST', $route, $post_data);
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testShowReviewGET()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $last_review = end($this->protocol_revision_repository->findAll());
        $review_id = $last_review->getId();



        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_initial_committee_review_show_review', 
            array("protocol_id" => $protocol_id, "protocol_revision_id" => $review_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function endReviewGET()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('protocol_end_review', array("protocol_id" => $protocol_id), false);
        
        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function endReviewPOST()
    {
        // getting last id
        $last_protocol = end($this->protocol_repository->findAll());
        $protocol_id = $last_protocol->getId();

        $client = $this->client;
        $route = $client->getContain-er()->get('router')->generate('protocol_end_review', array("protocol_id" => $protocol_id), false);

        file_put_contents("/tmp/photo.jpg", "test");

        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new UploadedFile(
            $file,
            'new_image.png'
        );

        $post_data = array(
            "draft-opinion" => "A",
            "final-decision" => $file,
        );
        
        $client->request('POST', $route, $post_data);
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    
}
