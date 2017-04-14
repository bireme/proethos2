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

use Doctrine\ORM\EntityRepository;


class CRUDControllerTest extends WebTestCase
{
    var $meeting_id = NULL;
    var $protocol_repository;
    var $submission_repository;
    var $meeting_repository;
    var $faq_repository;
    var $document_repository;
    var $user_repository;
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
        $this->user_repository = $this->_em->getRepository('Proethos2ModelBundle:User');

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
        $all_meetings = $this->meeting_repository->findAll();
        $last_meeting = end($all_meetings);
        $this->meeting_id = $last_meeting->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_show', array("meeting_id" => $this->meeting_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateMeetingGET()
    {
        // getting last id
        $all_meetings = $this->meeting_repository->findAll();
        $last_meeting = end($all_meetings);
        $this->meeting_id = $last_meeting->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_update', array("meeting_id" => $this->meeting_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateMeetingPOST()
    {
        // getting last id
        $all_meetings = $this->meeting_repository->findAll();
        $last_meeting = end($all_meetings);
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
        $all_meetings = $this->meeting_repository->findAll();
        $last_meeting = end($all_meetings);
        $this->meeting_id = $last_meeting->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_meeting_delete', array("meeting_id" => $this->meeting_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteMeetingPOST()
    {
        // getting last id
        $all_meetings = $this->meeting_repository->findAll();
        $last_meeting = end($all_meetings);
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

    public function testListCommitteeProtocolExportToCSV()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_protocol_list', array(), false);

        $client->request('GET', $route . "?output=csv");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListInvestigatorProtocolGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_investigator_protocol_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // getting last id
        $all_submissions = $this->submission_repository->findAll();
        $last_submission = end($all_submissions);
        $submission_id = $last_submission->getId();

        $this->_em->remove($last_submission->getProtocol());
        $this->_em->flush();
    }

    public function testListFaqPOST()
    {
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
        $all_questions = $this->faq_repository->findAll();
        $last_question = end($all_questions);
        $this->faq_id = $last_question->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_faq_update', array("faq_id" => $this->faq_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateFaqPOST()
    {
        // getting last id
        $all_questions = $this->faq_repository->findAll();
        $last_question = end($all_questions);
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
        $all_questions = $this->faq_repository->findAll();
        $last_question = end($all_questions);
        $this->faq_id = $last_question->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_faq_delete', array("faq_id" => $this->faq_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteFaqPOST()
    {
        // getting last id
        $all_questions = $this->faq_repository->findAll();
        $last_question = end($all_questions);
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
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_list', array(), false);

        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        // imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new UploadedFile(
            $file,
            'new_image.png'
        );

        $client->request('POST', $route, array(
            'title' => "Teste de Documento",
            'description' => "Descrição do documento",
            'roles' => array(2, 3),
            'status' => true,
        ), array('file' => $image, ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateCommitteeDocumentGET()
    {
        // getting last id
        $all_documents = $this->document_repository->findAll();
        $last_document = end($all_documents);
        $this->document_id = $last_document->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_update', array("document_id" => $this->document_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateCommitteeDocumentPOST()
    {
        // getting last id
        $all_documents = $this->document_repository->findAll();
        $last_document = end($all_documents);
        $this->document_id = $last_document->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_update', array("document_id" => $this->document_id), false);

        $client->request('POST', $route, array(
            'title' => "Teste de Documento2233",
            'description' => "Descrição do documento",
            'roles' => array(2, 3),
            'status' => true,
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testDeleteCommitteeDocumentGET()
    {
        // getting last id
        $all_documents = $this->document_repository->findAll();
        $last_document = end($all_documents);
        $this->document_id = $last_document->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_delete', array("document_id" => $this->document_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteCommitteeDocumentPOST()
    {
        // getting last id
        $all_documents = $this->document_repository->findAll();
        $last_document = end($all_documents);
        $this->document_id = $last_document->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_document_delete', array("document_id" => $this->document_id), false);

        $client->request('POST', $route, array(
            'delete' => "true",
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testDocumentGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_document_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListCommitteeUserGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListCommitteeUserPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_list', array(), false);

        $client->request('POST', $route, array(
            'name' => "Moacir",
            'username' => md5(date("YmdHis")),
            'email' => md5(date("YmdHis")) . "@cir.com",
            'country' => 76,
            'institution' => "BIREME",
            'status' => "true",
        ));

        // print $client->getResponse()->getContent();
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testListCommitteeUserExportToCSV()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_list', array(), false);

        $client->request('GET', $route . "?output=csv");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateCommitteeUserGET()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_update', array("user_id" => $this->user_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateCommitteeUserPOST()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_update', array("user_id" => $this->user_id), false);

        $client->request('POST', $route, array(
            'name' => "Moacir Mo",
            'country' => 76,
            'institution' => "BIREME",
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testGetKeyToChangePasswordCommitteeUserGET()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_get_key_to_change_password', array("user_id" => $this->user_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateCommitteeUserRoleGET()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_role_update', array("user_id" => $this->user_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateUserRolePOST()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_role_update', array("user_id" => $this->user_id), false);

        $client->request('POST', $route, array(
            'investigator' => "on",
            'secretary' => "on",
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateUserProfileGET()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_user_profile_update', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateUserProfilePOST()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_user_profile_update', array(), false);

        $client->request('POST', $route, array(
            'name' => "Moacir Moda",
            'country' => 76,
            'institution' => "BIREME",
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }



    public function testDeleteCommitteeUserGET()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_delete', array("user_id" => $this->user_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteCommitteeUserPOST()
    {
        // getting last id
        $all_users = $this->user_repository->findAll();
        $last_user = end($all_users);
        $this->user_id = $last_user->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_committee_user_delete', array("user_id" => $this->user_id), false);

        $client->request('POST', $route, array(
            'delete' => "true",
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testContactGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_contact_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListHelpGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_help_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateHelpGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_help_update', array('help_id' => 1), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateHelpPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_help_update', array('help_id' => 1), false);

        $client->request('POST', $route, array(
            "help-message" => "Teste de Mensagem",
        ));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShowHelpGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_help_show', array('help_id' => 1), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCheckHelpGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_help_check', array('help_id' => 1), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListConfigurationGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_configuration_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testUpdateConfigurationGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_configuration_update', array('configuration_id' => 2), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateConfigurationPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_configuration_update', array('configuration_id' => 2), false);

        $client->request('POST', $route, array(
            "configuration-value" => "BIREME/OPAS/OMS",
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testListControlledListUploadTypeExtensionGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_upload_type_extension_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListControlledListUploadTypeExtensionPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_upload_type_extension_list', array(), false);

        $client->request('POST', $route, array(
            "extension" => "jpeg",
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListUploadTypeExtensionGET()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:UploadTypeExtension');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_upload_type_extension_update', array('item_id' => $last_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListUploadTypeExtensionPOST()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:UploadTypeExtension');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_upload_type_extension_update', array('item_id' => $last_id), false);

        $client->request('POST', $route, array(
            "extension" => "jpeg",
        ));

        $status_code = $client->getResponse()->getStatusCode();

        $this->_em->remove($last_item);
        $this->_em->flush();

        $this->assertEquals(301, $status_code);

    }

    public function testListControlledListUploadTypeGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_upload_type_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListControlledListUploadTypePOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_upload_type_list', array(), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
            "extensions" => array("1", "2"),
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListUploadTypeGET()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:UploadType');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_upload_type_update', array('item_id' => $last_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListUploadTypePOST()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:UploadType');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_upload_type_update', array('item_id' => $last_id), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
            "extensions" => array("1", "2"),
        ));

        $status_code = $client->getResponse()->getStatusCode();

        $this->_em->remove($last_item);
        $this->_em->flush();

        $this->assertEquals(301, $status_code);
    }

    public function testListControlledListRecruitmentStatusGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_recruitment_status_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListControlledListRecruitmentStatusPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_recruitment_status_list', array(), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
        ));

        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListRecruitmentStatusGET()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:RecruitmentStatus');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_recruitment_status_update', array('item_id' => $last_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListRecruitmentStatusPOST()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:RecruitmentStatus');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_recruitment_status_update', array('item_id' => $last_id), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
        ));

        $status_code = $client->getResponse()->getStatusCode();

        $this->_em->remove($last_item);
        $this->_em->flush();

        $this->assertEquals(301, $status_code);
    }

    public function testListControlledListMonitoringActionGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_monitoring_action_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListControlledListMonitoringActionPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_monitoring_action_list', array(), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListMonitoringActionGET()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:MonitoringAction');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_monitoring_action_update', array('item_id' => $last_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListMonitoringActionPOST()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:MonitoringAction');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_monitoring_action_update', array('item_id' => $last_id), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
        ));

        $status_code = $client->getResponse()->getStatusCode();

        $this->_em->remove($last_item);
        $this->_em->flush();

        $this->assertEquals(301, $status_code);
    }

    public function testListControlledListClinicalTrialNameGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_clinical_trial_name_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListControlledListClinicalTrialNamePOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_clinical_trial_name_list', array(), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
            'code' => 'test',
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListClinicalTrialNameGET()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:MonitoringAction');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_clinical_trial_name_update', array('item_id' => $last_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListClinicalTrialNamePOST()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:MonitoringAction');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_clinical_trial_name_update', array('item_id' => $last_id), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
            'code' => 'test',
        ));

        $status_code = $client->getResponse()->getStatusCode();

        $this->_em->remove($last_item);
        $this->_em->flush();

        $this->assertEquals(301, $status_code);
    }

    public function testListControlledListGenderGET()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_gender_list', array(), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testListControlledListGenderPOST()
    {
        $client = $this->client;
        $route = $client->getContainer()->get('router')->generate('crud_admin_controlled_list_gender_list', array(), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
        ));
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListGenderGET()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:Gender');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_gender_update', array('item_id' => $last_id), false);

        $client->request('GET', $route);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUpdateControlledListGenderPOST()
    {
        // getting last id
        $repository = $this->_em->getRepository('Proethos2ModelBundle:Gender');
        $all_items = $repository->findAll();
        $last_item = end($all_items);
        $last_id = $last_item->getId();

        $client = $this->client;
        $route = $client->getContainer()
            ->get('router')->generate('crud_admin_controlled_list_gender_update', array('item_id' => $last_id), false);

        $client->request('POST', $route, array(
            "name" => "Gerado no Teste",
        ));

        $status_code = $client->getResponse()->getStatusCode();

        $this->_em->remove($last_item);
        $this->_em->flush();

        $this->assertEquals(301, $status_code);
    }

}
