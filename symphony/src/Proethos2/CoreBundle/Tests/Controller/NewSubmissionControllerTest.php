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

class NewSubmissionControllerTest extends WebTestCase
{
    var $submission_repository;
    var $protocol_repository;
    var $client;
    var $_em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_em = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->submission_repository = $this->_em->getRepository('Proethos2ModelBundle:Submission');
        $this->protocol_repository = $this->_em->getRepository('Proethos2ModelBundle:Protocol');

        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'admin'
        ));
    }

    public function testFirstStepGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_first_step', array(), false);

        $client->request('GET', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testFirstStepPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_first_step', array(), false);

        $client->request('POST', $route, array(
            "language"          => "en",
            "is_clinical_trial" => "no",
            "is_consultation"   => "no",
            "scientific_title"  => "Cientitif Title",
            "public_title"      => "Public Title",
            "title_acronym"     => "Title Acronymous",
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testFirstStepCreatedProtocolGET()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_first_created_protocol_step', array("submission_id" => $submission_id), false);

        $client->request('GET', $route);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testFirstStepCreatedProtocolPOST()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_first_created_protocol_step', array("submission_id" => $submission_id), false);

        $client->request('POST', $route, array(
            "language"          => "en",
            "is_clinical_trial" => "no",
            "is_consultation"   => "no",
            "scientific_title"  => "Cientitif Title",
            "public_title"      => "Public Title",
            "title_acronym"     => "Title Acronymous",
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testSecondStepGET()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_second_step', array("submission_id" => $submission_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSecondStepPOST()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_second_step', array("submission_id" => $submission_id), false);

        $client->request('POST', $route, array(
            "submission_id" => $submission_id,
            "abstract" => "Abstract",
            "keywords" => "Keywords",
            "introduction" => "Introduction",
            "justify" => "Justify",
            "goals" => "Goals",
        ));

        // print $client->getResponse()->getContent();
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testThirdStepGET()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_third_step', array("submission_id" => $submission_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testThirdStepPOST()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_third_step', array("submission_id" => $submission_id), false);

        $client->request('POST', $route, array(
            "submission_id" => $submission_id,
            "study-design" => "Study design",
            "health-condition" => "Health Condition or Problem Studied ",
            "gender" => "2",
            "sample-size" => "2",
            "minimum-age" => "4",
            "maximum-age" => "6",
            "inclusion-criteria" => "Inclusion criteria",
            "exclusion-criteria" => "Exclusion criteria",
            "recruitment-init-date" => "2099-05-31",
            "recruitment-status" => "1",
            "country[0][country]" => "BR",
            "country[0][participants]" => "6",
            "interventions" => "Interventions",
            "primary-outcome" => "Primary outcomes ",
            "secondary-outcome" => "Secondary outcomes ",
            "general-procedures" => "General Procedures",
            "analysis-plan" => "Analysis Plan",
            "ethical-considerations" => "Ethical Considerations"
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testFourthStepGET()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_fourth_step', array("submission_id" => $submission_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testFourthStepPOST()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_fourth_step', array("submission_id" => $submission_id), false);

        $client->request('POST', $route, array(
            "submission_id" => $submission_id,
            "budget[][description]" => "Budget teste",
            "budget[][quantity]" => "3",
            "budget[][unit_cost]" => "45.00",
            "funding-source" => "Funding source",
            "primary-sponsor" => "Primary Sponsor",
            "secondary-sponsor" => "Secondary Sponsor",
            "schedule[][description]" => "task 2",
            "schedule[][init]" => "2017-05-28",
            "schedule[][end]" => "2017-05-31"
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testFifthStepGET()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_fifth_step', array("submission_id" => $submission_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testFifthStepPOST()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_fifth_step', array("submission_id" => $submission_id), false);

        $client->request('POST', $route, array(
            "submission_id" => $submission_id,
            "bibliography" => "Bibliography",
            "sscientific-contact" => "Scientific Contact",
            "prior-ethical-approval" => "Y"
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testSixthfStepGET()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_sixth_step', array("submission_id" => $submission_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSixthStepPOST()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_sixth_step', array("submission_id" => $submission_id), false);

        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new UploadedFile(
            $file,
            'new_image.png'
        );

        $client->request('POST', $route, array(
            "submission_id" => $submission_id,
            "new-atachment-type" => "1",
            "new-atachment-file" => $image,
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testSeventhStepGET()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_seventh_step', array("submission_id" => $submission_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSeventhStepPOST()
    {
        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('submission_new_seventh_step', array("submission_id" => $submission_id), false);

        $client->request('POST', $route, array(
            "submission_id" => $submission_id,
            "accept-terms" => "on",
            // BUGFIX: See https://github.com/bireme/proethos2/blob/master/doc/continuous-integration.md
            // needs to has this extra, to ignore pdf, otherwise, tests don't pass
            "extra" => "no-pdf"
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }
}
