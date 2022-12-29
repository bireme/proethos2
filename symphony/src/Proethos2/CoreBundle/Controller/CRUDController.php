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


namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Intl\Intl;
use Symfony\Component\HttpFoundation\Response;

use Proethos2\CoreBundle\Util\Util;
use Proethos2\CoreBundle\Util\CSVResponse;
use Proethos2\CoreBundle\Util\Security;

use Proethos2\ModelBundle\Entity\Meeting;
use Proethos2\ModelBundle\Entity\Faq;
use Proethos2\ModelBundle\Entity\Document;
use Proethos2\ModelBundle\Entity\User;
use Proethos2\ModelBundle\Entity\UploadTypeExtension;
use Proethos2\ModelBundle\Entity\UploadType;
use Proethos2\ModelBundle\Entity\RecruitmentStatus;
use Proethos2\ModelBundle\Entity\MonitoringAction;
use Proethos2\ModelBundle\Entity\ClinicalTrialName;
use Proethos2\ModelBundle\Entity\Gender;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class CRUDController extends Controller
{
    /**
     * @Route("/committee/meeting", name="crud_committee_meeting_list")
     * @Template()
     */
    public function listMeetingAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $meeting_repository = $em->getRepository('Proethos2ModelBundle:Meeting');

        $meetings = $meeting_repository->findBy(array(), array('date' => 'DESC'));

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No meeting found'));
        }

        // serach parameter
        $search_query = $request->query->get('q');
        if($search_query) {
            $meetings = $meeting_repository->createQueryBuilder('m')
               ->where('m.subject LIKE :query')
               ->setParameter('query', "%". $search_query ."%")
               ->addOrderBy('m.date', 'DESC')
               ->getQuery()
               ->getResult();
        }

        $output['meetings'] = $meetings;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-meeting', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            if(isset($post_data['new-meeting-date'])) {

                // checking required fields
                foreach(array('new-meeting-date', 'new-meeting-subject', 'new-meeting-content') as $field) {
                    if(!isset($post_data[$field]) or empty($post_data[$field])) {
                        $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                        return $output;
                    }
                }

                $meeting = new Meeting();
                $meeting->setDate(new \DateTime($post_data['new-meeting-date']));
                $meeting->setSubject($post_data['new-meeting-subject']);
                $meeting->setContent($post_data['new-meeting-content']);

                $em->persist($meeting);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("Meeting created with success."));
                return $this->redirectToRoute('crud_committee_meeting_list', array(), 301);
            }

        }

        return $output;
    }

    /**
     * @Route("/committee/meeting/{meeting_id}", name="crud_committee_meeting_update")
     * @Template()
     */
    public function updateMeetingAction($meeting_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $meeting_repository = $em->getRepository('Proethos2ModelBundle:Meeting');
        $upload_type_extension_repository = $em->getRepository('Proethos2ModelBundle:UploadTypeExtension');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        // getting the current meeting
        $meeting = $meeting_repository->find($meeting_id);
        $output['meeting'] = $meeting;

        if (!$meeting) {
            throw $this->createNotFoundException($translator->trans('No meeting found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-meeting', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();
            // echo "<pre>"; print_r($post_data); echo "</pre>"; die();

            $file = $request->files->get('file');
            // echo "<pre>"; print_r($file); echo "</pre>"; die();

            if ( $file ) {
                // getting the upload type extensions
                $upload_type_extensions = $upload_type_extension_repository->findAll();

                $file_ext = '.'.$file->getClientOriginalExtension();
                $ext_formats = array_map(function($obj) { return $obj->getExtension(); }, $upload_type_extensions);
                if ( !in_array($file_ext, $ext_formats) ) {
                    $session->getFlashBag()->add('error', $translator->trans("File extension not allowed"));
                    return $output;
                }

                $meeting->setFile($file);
            }

            // checking required files
            foreach(array('new-meeting-date', 'new-meeting-subject', 'new-meeting-content') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $meeting->setDate(new \DateTime($post_data['new-meeting-date']));
            $meeting->setSubject($post_data['new-meeting-subject']);
            $meeting->setContent($post_data['new-meeting-content']);

            $em->persist($meeting);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Meeting updated with success."));
            return $this->redirectToRoute('crud_committee_meeting_list', array(), 301);
        }

        return $output;
    }

     /**
     * @Route("/committee/meeting/{meeting_id}/show", name="crud_committee_meeting_show")
     * @Template()
     */
    public function showMeetingAction($meeting_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $meeting_repository = $em->getRepository('Proethos2ModelBundle:Meeting');
        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug()) and !in_array('member-of-committee', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        // getting the current meeting
        $meeting = $meeting_repository->find($meeting_id);
        $output['meeting'] = $meeting;

        if (!$meeting) {
            throw $this->createNotFoundException($translator->trans('No meeting found'));
        }

        $protocols = $protocol_repository->findBy(array('meeting' => $meeting));
        $output['protocols'] = $protocols;

        $referer = $request->headers->get('referer');

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('delete-attachment', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // echo "<pre>"; print_r($post_data); echo "</pre>"; die();

            if ( 'yes' == $post_data['delete-attachment'] ) {
                $meeting->setFilename(NULL);
                $meeting->setFilepath(NULL);

                $em->persist($meeting);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("File removed with success."));
                return $this->redirect($referer, 301);
            }
        }

        return $output;
    }

    /**
     * @Route("/committee/meeting/{meeting_id}/upload", name="crud_committee_meeting_upload")
     * @Template()
     */
    public function meetingUploadAction($meeting_id) {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $meeting_repository = $em->getRepository('Proethos2ModelBundle:Meeting');

        $util = new Util($this->container, $this->getDoctrine());

        $meeting = $meeting_repository->find($meeting_id);
        $root_dir = $this->get('kernel')->getRootDir();
        $filepath = $root_dir.'/..'.$meeting->getUri();
        $filename = end(explode('_', $meeting->getFilename(), 2));
        // $file_ext = strtolower(substr(strrchr($meeting->getFilename(), '.'), 1));

        $response = new BinaryFileResponse($filepath);
        // Content-Disposition: DISPOSITION_INLINE (browser) or DISPOSITION_ATTACHMENT (download)
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $filename
        );

        return $response;
    }

    /**
     * @Route("/committee/meeting/{meeting_id}/delete", name="crud_committee_meeting_delete")
     * @Template()
     */
    public function deleteMeetingAction($meeting_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $meeting_repository = $em->getRepository('Proethos2ModelBundle:Meeting');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        // getting the current meeting
        $meeting = $meeting_repository->find($meeting_id);
        $output['meeting'] = $meeting;

        if (!$meeting) {
            throw $this->createNotFoundException($translator->trans('No meeting found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('delete-meeting', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('meeting-delete') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $em->remove($meeting);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Meeting deleted with success."));
            return $this->redirectToRoute('crud_committee_meeting_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/committee/protocol", name="crud_committee_protocol_list")
     * @Template()
     */
    public function listCommitteeProtocolAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');

        // serach  and status parameter
        $status_array = array('S', 'R', 'I', 'E', 'H', "F", "A", "N", "C", "X", "T");
        $search_query = $request->query->get('q');
        $status_query = $request->query->get('status');

        if(!empty($status_query))
            $status_array = array($status_query);

        $query = $protocol_repository->createQueryBuilder('p')
           ->join('p.main_submission', 's')
           ->join('s.owner', 'o')
           ->where("(s.publicTitle LIKE :query OR p.code LIKE :query OR o.name LIKE :query) AND p.status IN (:status)")
           ->orderBy("p.created", 'DESC')
           ->setParameter('query', "%". $search_query ."%")
           ->setParameter('status', $status_array);

        $protocols = $query->getQuery()->getResult();
        $output['protocols'] = $protocols;

        // output parameter
        $output_parameter = $request->query->get('output');
        if($output_parameter == 'csv') {
            $csv_headers = array('CODE', 'ID', 'OWNER', 'STATUS', 'PUBLIC TITLE', 'TYPE', 'RECRUITMENT INIT DATE',
                'REJECT REASON', 'COMMITTEE SCREENING', 'OPINIONS REQUIRED', 'DATE INFORMED', 'UPDATED IN', 'REVISED IN',
                'DECISION IN', 'MEETING', 'MONITORING ACTION', 'NEXT DATE OF MONITORING ACTION');
            $csv_output = array();
            foreach($protocols as $protocol) {
                $type = "Research";
                if ( $protocol->getMainSubmission()->getIsClinicalTrial() ) { $type = "Clinical Trial"; }
                if ( $protocol->getMainSubmission()->getIsConsultation() ) { $type = "Consultation"; }

                $current_line = array();
                $current_line[] = $protocol->getCode();
                $current_line[] = $protocol->getId();
                $current_line[] = $protocol->getOwner()->getUsername();
                $current_line[] = $protocol->getStatusLabel();
                $current_line[] = $protocol->getMainSubmission()->getPublicTitle();
                // $current_line[] = $protocol->getMainSubmission()->getIsClinicalTrial() ? "Clinical Trial" : "Research";
                $current_line[] = $type;
                $current_line[] = $protocol->getMainSubmission()->getRecruitmentInitDate() ? $protocol->getMainSubmission()->getRecruitmentInitDate()->format("Y-m-d H:i") : "";
                $current_line[] = $protocol->getRejectReason();
                $current_line[] = $protocol->getCommitteeScreening();
                $current_line[] = $protocol->getOpinionRequired();
                $current_line[] = $protocol->getDateInformed() ? $protocol->getDateInformed()->format("Y-m-d H:i") : "";
                $current_line[] = $protocol->getUpdatedIn() ? $protocol->getUpdatedIn()->format("Y-m-d H:i") : "";
                $current_line[] = $protocol->getRevisedIn() ? $protocol->getRevisedIn()->format("Y-m-d H:i") : "";
                $current_line[] = $protocol->getDecisionIn() ? $protocol->getDecisionIn()->format("Y-m-d H:i") : "";
                $current_line[] = $protocol->getMeeting() ? $protocol->getMeeting()->getSubject() : "";
                $current_line[] = $protocol->getMonitoringAction() ? $protocol->getMonitoringAction()->getName() : "";
                $current_line[] = $protocol->getMonitoringActionNextDate() ? $protocol->getMonitoringActionNextDate()->format("Y-m-d H:i") : "";
                $csv_output[] = $current_line;
            }

            $response = new CSVResponse( $csv_output, 200, $csv_headers );
            $response->setFilename( "proethos2-protocols.csv" );
            return $response;
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

        }

        return $output;
    }

    /**
     * @Route("/investigator/protocol", name="crud_investigator_protocol_list")
     * @Template()
     */
    public function listInvestigatorProtocolAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getSession()->get('_locale');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $configuration_repository = $em->getRepository('Proethos2ModelBundle:Configuration');

        $protocol_checklist = $configuration_repository->findOneBy(array('key' => 'protocol.checklist'));
        $translations = $trans_repository->findTranslations($protocol_checklist);
        $text = $translations[$locale];

        if ( $text ) {
            $output['protocol_checklist'] = $text['value'];
        } else {
            $output['protocol_checklist'] = $protocol_checklist->getValue();
        }

        $show_protocol_checklist = $session->get('show_protocol_checklist');
        if ( NULL === $show_protocol_checklist ) {
            $session->set('show_protocol_checklist', true);
        } else {
            $session->set('show_protocol_checklist', false);
        }

        // serach  and status parameter
        $status_array = array('D', 'S', 'R', 'I', 'E', 'H', 'F', 'A', 'N', 'C', 'X', "T");
        $search_query = $request->query->get('q');
        $status_query = $request->query->get('status');

        if(!empty($status_query))
            $status_array = array($status_query);

        $query = $protocol_repository->createQueryBuilder('p')
           ->join('p.main_submission', 's')
           ->join('s.owner', 'o')
           ->leftJoin('s.team', 't')
           ->leftJoin('p.revision', 'r')
           ->where("(s.publicTitle LIKE :query OR p.code LIKE :query OR o.name LIKE :query) AND p.status IN (:status) AND ((s.owner = :owner OR t = :owner) OR r.member = :owner)")
           ->orderBy("p.created", 'DESC')
           ->setParameter('query', "%". $search_query ."%")
           ->setParameter('status', $status_array)
           ->setParameter('owner', $user);

        $protocols = $query->getQuery()->getResult();
        $output['protocols'] = $protocols;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

        }

        return $output;
    }

    /**
     * @Route("/committee/faq", name="crud_committee_faq_list")
     * @Template()
     */
    public function listCommitteeFaqAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $faq_repository = $em->getRepository('Proethos2ModelBundle:Faq');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No FAQ found'));
        }

        $faqs = $faq_repository->findAll();

        // serach parameter
        $search_query = $request->query->get('q');
        if($search_query) {
            $faqs = $faq_repository->createQueryBuilder('m')
               ->where('m.question LIKE :query')
               ->setParameter('query', "%". $search_query ."%")
               ->getQuery()
               ->getResult();
        }

        $output['faqs'] = $faqs;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-faq', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('new-question', 'new-question-answer') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $question = new Faq();
            $question->setTranslatableLocale('en');

            $question->setQuestion($post_data['new-question']);
            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["new-question-$locale"])) {
                    $trans_repository = $trans_repository->translate($question, 'question', $locale, $post_data["new-question-$locale"]);
                }
            }

            $question->setAnswer($post_data['new-question-answer']);
            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["new-question-answer-$locale"])) {
                    $trans_repository = $trans_repository->translate($question, 'answer', $locale, $post_data["new-question-answer-$locale"]);
                }
            }

            if(isset($post_data['new-question-status'])) {
                $question->setStatus(true);
            }

            $em->persist($question);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Question created with success."));
            return $this->redirectToRoute('crud_committee_faq_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/committee/faq/{faq_id}", name="crud_committee_faq_update")
     * @Template()
     */
    public function updateCommitteeFaqAction($faq_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $faq_repository = $em->getRepository('Proethos2ModelBundle:Faq');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No FAQ found'));
        }

        // getting the current faq
        $question = $faq_repository->find($faq_id);
        $output['question'] = $question;

        if (!$question) {
            throw $this->createNotFoundException($translator->trans('No FAQ found'));
        }

        $translations = $trans_repository->findTranslations($question);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-faq', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('new-question', 'new-question-answer') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $question->setTranslatableLocale('en');

            $question->setQuestion($post_data['new-question']);
            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["new-question-$locale"])) {
                    $trans_repository = $trans_repository->translate($question, 'question', $locale, $post_data["new-question-$locale"]);
                }
            }

            $question->setAnswer($post_data['new-question-answer']);
            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["new-question-answer-$locale"])) {
                    $trans_repository = $trans_repository->translate($question, 'answer', $locale, $post_data["new-question-answer-$locale"]);
                }
            }

            $question->setStatus(false);
            if(isset($post_data['new-question-status'])) {
                $question->setStatus(true);
            }

            $em->persist($question);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Question updated with success."));
            return $this->redirectToRoute('crud_committee_faq_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/committee/faq/{faq_id}/delete", name="crud_committee_faq_delete")
     * @Template()
     */
    public function deleteCommitteeFaqAction($faq_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $faq_repository = $em->getRepository('Proethos2ModelBundle:Faq');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No FAQ found'));
        }

        // getting the current faq
        $question = $faq_repository->find($faq_id);
        $output['question'] = $question;

        if (!$question) {
            throw $this->createNotFoundException($translator->trans('No FAQ found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('delete-faq', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('question-delete') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $this->redirectToRoute('crud_committee_faq_list', array(), 301);
                }
            }

            $em->remove($question);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Question deleted with success."));
            return $this->redirectToRoute('crud_committee_faq_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/public/faq", name="crud_faq_list")
     * @Template()
     */
    public function listFaqAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $faq_repository = $em->getRepository('Proethos2ModelBundle:Faq');

        $questions = $faq_repository->findAll();
        $output['questions'] = $questions;

        return $output;
    }

    /**
     * @Route("/committee/documents", name="crud_committee_document_list")
     * @Template()
     */
    public function listCommitteeDocumentAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $document_repository = $em->getRepository('Proethos2ModelBundle:Document');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');
        $upload_type_extension_repository = $em->getRepository('Proethos2ModelBundle:UploadTypeExtension');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No document found'));
        }

        $documents = $document_repository->findAll();

        // serach parameter
        $search_query = $request->query->get('q');
        if($search_query) {
            $documents = $document_repository->createQueryBuilder('m')
               ->where('m.title LIKE :query')
               ->setParameter('query', "%". $search_query ."%")
               ->getQuery()
               ->getResult();
        }

        $output['documents'] = $documents;

        $roles = $role_repository->findAll();
        $output['roles'] = $roles;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-document', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();
            // echo "<pre>"; print_r($post_data); echo "</pre>"; die();

            $file = $request->files->get('file');
            // echo "<pre>"; print_r($file); echo "</pre>"; die();

            if(empty($file)) {
                $session->getFlashBag()->add('error', $translator->trans("Field 'file' is required."));
                return $output;
            }
            
            // getting the upload type extensions
            $upload_type_extensions = $upload_type_extension_repository->findAll();

            $file_ext = '.'.$file->getClientOriginalExtension();
            $ext_formats = array_map(function($obj) { return $obj->getExtension(); }, $upload_type_extensions);
            if ( !in_array($file_ext, $ext_formats) ) {
                $session->getFlashBag()->add('error', $translator->trans("File extension not allowed"));
                return $output;
            }

            // checking required fields
            foreach(array('title', 'roles') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $document = new Document();
            foreach($post_data['roles'] as $role) {
                $role = $role_repository->find($role);
                $document->addRole($role);
            }

            $document->setTitle($post_data['title']);
            $document->setDescription($post_data['description']);
            $document->setFile($file);

            if(isset($post_data['status'])) {
                $document->setStatus(true);
            }

            $em->persist($document);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Question created with success."));
            return $this->redirectToRoute('crud_committee_document_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/committee/document/{document_id}", name="crud_committee_document_update")
     * @Template()
     */
    public function updateCommitteeDocumentAction($document_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $document_repository = $em->getRepository('Proethos2ModelBundle:Document');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No document found'));
        }

        // getting the current document
        $document = $document_repository->find($document_id);
        $output['document'] = $document;

        if (!$document) {
            throw $this->createNotFoundException($translator->trans('No document found'));
        }

        $roles = $role_repository->findAll();
        $output['roles'] = $roles;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-document', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('title',) as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            foreach($document->getRoles() as $role) {
                $document->removeRole($role);
            }

            foreach($post_data['roles'] as $role) {
                $role = $role_repository->find($role);
                $document->addRole($role);
            }

            $document->setTitle($post_data['title']);
            $document->setDescription($post_data['description']);

            $document->setStatus(false);
            if(isset($post_data['status'])) {
                $document->setStatus(true);
            }

            $em->persist($document);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Document updated with success."));
            return $this->redirectToRoute('crud_committee_document_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/committee/document/{document_id}/delete", name="crud_committee_document_delete")
     * @Template()
     */
    public function deleteCommitteeDocumentAction($document_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $document_repository = $em->getRepository('Proethos2ModelBundle:Document');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No document found'));
        }

        // getting the current document
        $document = $document_repository->find($document_id);
        $output['document'] = $document;

        if (!$document) {
            throw $this->createNotFoundException($translator->trans('No document found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('delete-document', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('delete') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $em->remove($document);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Document deleted with success."));
            return $this->redirectToRoute('crud_committee_document_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/document", name="crud_document_list")
     * @Template()
     */
    public function listDocumentAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $document_repository = $em->getRepository('Proethos2ModelBundle:Document');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        // if (!in_array('secretary', $user_logged->getRolesSlug())) {
        //     throw $this->createNotFoundException($translator->trans('No document found'));
        // }

        $documents = $document_repository->findAll();

        // serach parameter
        $search_query = $request->query->get('q');
        if($search_query) {
            $documents = $document_repository->createQueryBuilder('m')
               ->where('m.title LIKE :query')
               ->setParameter('query', "%". $search_query ."%")
               ->getQuery()
               ->getResult();
        }

        $output['documents'] = $documents;

        $roles = $role_repository->findAll();
        $output['roles'] = $roles;

        return $output;
    }

    /**
     * @Route("/document/{document_id}/upload", name="crud_document_upload")
     * @Template()
     */
    public function documentUploadAction($document_id) {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $document_repository = $em->getRepository('Proethos2ModelBundle:Document');

        $util = new Util($this->container, $this->getDoctrine());

        $document = $document_repository->find($document_id);
        $root_dir = $this->get('kernel')->getRootDir();
        $filepath = $root_dir.'/..'.$document->getUri();
        // $file_ext = strtolower(substr(strrchr($document->getFilename(), '.'), 1));

        $response = new BinaryFileResponse($filepath);
        // Content-Disposition: DISPOSITION_INLINE (browser) or DISPOSITION_ATTACHMENT (download)
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $document->getFilename()
        );

        return $response;
    }

    /**
     * @Route("/committee/users", name="crud_committee_user_list")
     * @Template()
     */
    public function listCommitteeUserAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $util = new Util($this->container, $this->getDoctrine());

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');
        $country_repository = $em->getRepository('Proethos2ModelBundle:Country');

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        $auth_type = $this->container->getParameter('auth_type');
        $output['auth_type'] = $auth_type;

        $users = $user_repository->findAll();

        // search parameter
        $search_query = $request->query->get('q');
        if($search_query) {
            $users = $user_repository->createQueryBuilder('m')
               ->where('m.name LIKE :query')
               ->setParameter('query', "%". $search_query ."%")
               ->getQuery()
               ->getResult();
        }

        $output['users'] = $users;

        // output parameter
        $output_parameter = $request->query->get('output');
        if($output_parameter == 'csv') {
            $csv_headers = array('USERNAME', 'ID', 'EMAIL', 'ROLES', 'ACTIVE?', 'NAME', 'COUNTRY', 'INSTITUTION');
            $csv_output = array();
            foreach($users as $user) {
                $current_line = array();
                $current_line[] = $user->getId();
                $current_line[] = $user->getUsername();
                $current_line[] = $user->getEmail();
                $current_line[] = implode(",", $user->getRolesSlug());
                $current_line[] = $user->getIsActive() == 1 ? "YES" : "NO";
                $current_line[] = $user->getName();
                $current_line[] = $user->getCountry() ? $user->getCountry()->getName() : '';
                $current_line[] = $user->getInstitution();
                $csv_output[] = $current_line;
            }

            $response = new CSVResponse( $csv_output, 200, $csv_headers );
            $response->setFilename( "proethos2-users.csv" );
            return $response;
        }

        $roles = $role_repository->findAll();
        $output['roles'] = $roles;

        $countries = $country_repository->findBy(array(), array('name' => 'asc'));
        $output['countries'] = $countries;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            if ( 'oauth2' == $auth_type ) {
                throw $this->createNotFoundException($translator->trans('Authentication type not valid'));
            }

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-user', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('name', 'username', 'email', 'country', ) as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $user = $user_repository->findOneByUsername(Security::encrypt($post_data['username']));
            if($user) {
                $session->getFlashBag()->add('error', $translator->trans("Username already registered in platform."));
                return $output;
            }

            $user = $user_repository->findOneByEmail(Security::encrypt($post_data['email']));
            if($user) {
                $session->getFlashBag()->add('error', $translator->trans("Email already registered in platform."));
                return $output;
            }

            $country = $country_repository->find($post_data['country']);

            $user = new User();
            $user->setCountry($country);
            $user->setName($post_data['name']);
            $user->setUsername($post_data['username']);
            $user->setEmail($post_data['email']);
            $user->setInstitution($post_data['institution']);
            $user->setFirstAccess(false);

            if(isset($post_data['status'])) {
                $user->setIsActive(true);
            }

            // adding user role
            $user->addProethos2Role($role_repository->findOneBy(array('slug' => 'investigator')));

            $encoderFactory = $this->get('security.encoder_factory');
            $encoder = $encoderFactory->getEncoder($user);
            $salt = $user->getSalt(); // this should be different for every user
            $password = $encoder->encodePassword(md5(date("YmdHis")), $salt);
            $user->setPassword($password);

            // Send email to created user with the link to change the first password
            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

            $hashcode = $user->generateHashcode();
            $em->persist($user);
            $em->flush();

            // TODO need to get the relative path
            $url = $baseurl . "/public/account/reset_my_password?hashcode=" . $hashcode;
            
            $locale = $request->getSession()->get('_locale');
            $help = $help_repository->find(203);
            $translations = $trans_repository->findTranslations($help);
            $text = $translations[$locale];
            $body = ( $text ) ? $text['message'] : $help->getMessage();
            $body = str_replace("%reset_password_url%", $url, $body);
            $body = str_replace("\r\n", "<br />", $body);
            $body .= "<br /><br />";
            $body = $util->linkify($body);

            $message = \Swift_Message::newInstance()
            ->setSubject("[proethos2] " . $translator->trans("Set your password"))
            ->setFrom($util->getConfiguration('committee.email'))
            ->setTo($post_data['email'])
            ->setBody(
                $body
                ,
                'text/html'
            );

            $send = $this->get('mailer')->send($message);

            // user activity log
            $logger = $this->get('monolog.logger.security');
            $logger->info('User created successfully.', array('username' => $user->getUsername()));

            $em->persist($user);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("User created with success."));
            return $this->redirectToRoute('crud_committee_user_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/account/update_profile", name="crud_user_profile_update")
     * @Template()
     */
    public function updateUserProfileAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $country_repository = $em->getRepository('Proethos2ModelBundle:Country');

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $output['user'] = $user;

        $countries = $country_repository->findBy(array(), array('name' => 'asc'));
        $output['countries'] = $countries;

        $auth_type = $this->container->getParameter('auth_type');
        $output['auth_type'] = $auth_type;

        $referer = $request->headers->get('referer');

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-user-profile', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            $required_fields = array('email', 'name', 'country');
            if ( 'oauth2' == $auth_type ) array_shift($required_fields);

            // checking required fields
            foreach($required_fields as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $this->redirect($referer, 301);
                }
            }
            
            if ( $post_data['email'] != $user->getEmail() ) {
                $usr = $user_repository->findOneByEmail(Security::encrypt($post_data['email']));
                if($usr) {
                    $session->getFlashBag()->add('error', $translator->trans("Email already registered"));
                    return $this->redirect($referer, 301);
                }
            }

            $country = $country_repository->find($post_data['country']);

            $user->setCountry($country);
            $user->setName($post_data['name']);
            $user->setInstitution($post_data['institution']);
            if ( 'oauth2' != $auth_type ) $user->setEmail($post_data['email']);

            $em->persist($user);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("User updated with success."));
            return $this->redirect($referer, 301);
            // return $this->redirectToRoute('home', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/committee/user/{user_id}", name="crud_committee_user_update")
     * @Template()
     */
    public function updateCommitteeUserAction($user_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $util = new Util($this->container, $this->getDoctrine());

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');
        $country_repository = $em->getRepository('Proethos2ModelBundle:Country');

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        // getting the current user
        $user = $user_repository->find($user_id);
        $output['user'] = $user;

        if (!$user) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        $roles = $role_repository->findAll();
        $output['roles'] = $roles;

        $countries = $country_repository->findBy(array(), array('name' => 'asc'));
        $output['countries'] = $countries;

        $auth_type = $this->container->getParameter('auth_type');
        $output['auth_type'] = $auth_type;

        $referer = $request->headers->get('referer');

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-user', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // user status
            $user_status = $user->getIsActive();

            // getting post data
            $post_data = $request->request->all();

            $required_fields = array('email', 'name', 'country');
            if ( 'oauth2' == $auth_type ) array_shift($required_fields);

            // checking required fields
            foreach($required_fields as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $this->redirectToRoute('crud_committee_user_list', array(), 301);
                }
            }

            if ( $post_data['email'] != $user->getEmail() ) {
                $usr = $user_repository->findOneByEmail(Security::encrypt($post_data['email']));
                if($usr) {
                    $session->getFlashBag()->add('error', $translator->trans("Email already registered"));
                    return $this->redirectToRoute('crud_committee_user_list', array(), 301);
                }
            }

            $country = $country_repository->find($post_data['country']);

            $user->setCountry($country);
            $user->setName($post_data['name']);
            $user->setInstitution($post_data['institution']);
            if ( 'oauth2' != $auth_type ) $user->setEmail($post_data['email']);

            $user->setIsActive(false);
            if(isset($post_data['status'])) {
                $user->setIsActive(true);
            }

            $em->persist($user);
            $em->flush();

            if(isset($post_data['status']) && !$user_status) {
                $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                $url = $baseurl . $this->generateUrl('home');

                $locale = $request->getSession()->get('_locale');
                $help = $help_repository->find(204);
                $translations = $trans_repository->findTranslations($help);
                $text = $translations[$locale];
                $body = ( $text ) ? $text['message'] : $help->getMessage();
                $body = str_replace("%home_url%", $url, $body);
                $body = str_replace("\r\n", "<br />", $body);
                $body .= "<br /><br />";
                $body = $util->linkify($body);

                $message = \Swift_Message::newInstance()
                ->setSubject("[proethos2] " . $translator->trans("Confirmation of valid access to the Proethos2 platform"))
                ->setFrom($util->getConfiguration('committee.email'))
                ->setTo($user->getEmail())
                ->setBody(
                    $body
                    ,
                    'text/html'
                );

                $send = $this->get('mailer')->send($message);
            }

            // user activity log
            $logger = $this->get('monolog.logger.security');
            $logger->info('User profile updated successfully by ' . $user_logged->getUsername(), array('username' => $user->getUsername()));

            $session->getFlashBag()->add('success', $translator->trans("User updated with success."));
            // return $this->redirectToRoute('crud_committee_user_list', array(), 301);
            $_fragment = $post_data['_fragment'];
            return $this->redirect($referer.$_fragment, 301);

        }

        return $output;
    }

    /**
     * @Route("/committee/user/{user_id}/key", name="crud_committee_user_get_key_to_change_password")
     * @Template()
     */
    public function getKeyToChangePasswordCommitteeUserAction($user_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        // getting the current user
        $user = $user_repository->find($user_id);
        $output['user'] = $user;

        if (!$user) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $hashcode = $user->generateHashcode();
        $em->persist($user);
        $em->flush();

        // TODO need to get the relative path
        $output['url'] = $baseurl . "/public/account/reset_my_password?hashcode=" . $hashcode;

        return $output;
    }

    /**
     * @Route("/committee/user/{user_id}/role", name="crud_committee_user_role_update")
     * @Template()
     */
    public function updateCommitteeUserRoleAction($user_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        // getting the current user
        $user = $user_repository->find($user_id);
        $output['user'] = $user;

        if (!$user) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        $roles = $role_repository->findAll();
        $output['roles'] = $roles;

        $referer = $request->headers->get('referer');

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-user-role', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            foreach($roles as $role) {
                $user->removeProethos2Role($role);

                if(in_array($role->getSlug(), array_keys($post_data))) {
                    $user->addProethos2Role($role);
                }
            }

            $em->persist($user);
            $em->flush();

            // user activity log
            unset($post_data['token']);
            $logger = $this->get('monolog.logger.security');
            $logger->info('User roles updated successfully by ' . $user_logged->getUsername(), array('username' => $user->getUsername(), 'roles' => array_keys($post_data)));

            $session->getFlashBag()->add('success', $translator->trans("User updated with success."));
            // return $this->redirectToRoute('crud_committee_user_list', array(), 301);
            $_fragment = $post_data['_fragment'];
            return $this->redirect($referer.$_fragment, 301);
        }

        return $output;
    }

    /**
     * @Route("/committee/user/{user_id}/delete", name="crud_committee_user_delete")
     * @Template()
     */
    public function deleteCommitteeUserAction($user_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        // getting the current user
        $user = $user_repository->find($user_id);
        $output['user'] = $user;

        if (!$user) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('delete-user', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('delete') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $em->remove($user);
            $em->flush();

            // user activity log
            $logger = $this->get('monolog.logger.security');
            $logger->info('User deleted successfully by ' . $user_logged->getUsername(), array('username' => $user->getUsername()));

            $session->getFlashBag()->add('success', $translator->trans("User deleted with success."));
            return $this->redirectToRoute('crud_committee_user_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/public/contact", name="crud_contact_list")
     * @Template()
     */
    public function listContactAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $util = new Util($this->container, $this->getDoctrine());

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $output['committee_prefix'] = $util->getConfiguration('committee.prefix');
        $output['committee_name'] = $util->getConfiguration('committee.name');
        $output['committee_email'] = $util->getConfiguration('committee.email');
        $output['committee_address'] = $util->getConfiguration('committee.address');
        $output['committee_phones'] = $util->getConfiguration('committee.phones');

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('contact-form', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name', 'email', 'subject', 'message') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $locale = $request->getSession()->get('_locale');
            $help = $help_repository->find(205);
            $translations = $trans_repository->findTranslations($help);
            $text = $translations[$locale];
            $body = ( $text ) ? $text['message'] : $help->getMessage();
            $body = str_replace("%username%", $post_data['name'], $body);
            $body = str_replace("%email%", $post_data['email'], $body);
            $body = str_replace("%subject%", $post_data['subject'], $body);
            $body = str_replace("%message%", nl2br($post_data['message']), $body);
            $body = str_replace("\r\n", "<br />", $body);
            $body .= "<br /><br />";

            $message = \Swift_Message::newInstance()
            ->setSubject("[proethos2] " . $translator->trans("Message from plataform."))
            ->setFrom($output['committee_email'])
            ->setTo($output['committee_email'])
            ->setBody(
                $body
                ,
                'text/html'
            );

            $send = $this->get('mailer')->send($message);
            $session->getFlashBag()->add('success', $translator->trans("Message sent to administrators."));
            return $this->redirectToRoute('crud_contact_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/help", name="crud_admin_help_list")
     * @Template()
     */
    public function listHelpAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No help found'));
        }

        $helps = $help_repository->findBy(array("status" => true, "type" => "help"));

        $id = $request->query->get('id');
        if($id) {
            $helps = $help_repository->findBy(array("id" => $id));
        }

        $output['helps'] = $helps;
        return $output;
    }

    /**
     * @Route("/admin/help/{help_id}/update", name="crud_admin_help_update")
     * @Template()
     */
    public function updateHelpAction($help_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No help found'));
        }

        // getting the current help
        $help = $help_repository->find($help_id);
        $output['help'] = $help;


        if (!$help) {
            throw $this->createNotFoundException($translator->trans('No help found'));
        }

        $translations = $trans_repository->findTranslations($help);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-help', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('help-message-en') as $field) {

                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $help->setTranslatableLocale('en');
            $help->setMessage($post_data['help-message-en']);
            $help->setStatus(true);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["help-message-$locale"])) {
                    $trans_repository = $trans_repository->translate($help, 'message', $locale, $post_data["help-message-$locale"]);
                }
            }

            $em->persist($help);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Help updated with success."));
            return $this->redirectToRoute('crud_admin_help_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/help/{help_id}", name="crud_admin_help_show")
     * @Template()
     */
    public function showHelpAction($help_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');

        // getting the current help
        $help = $help_repository->find($help_id);
        $output['help'] = $help;

        if (!$help) {
            throw $this->createNotFoundException($translator->trans('No help found'));
        }

        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($help);
        $output['translations'] = $translations;

        return $output;
    }

    /**
     * @Route("/admin/help/{help_id}/check", name="crud_admin_help_check")
     */
    public function checkHelpAction($help_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No help found'));
        }

        // getting the current help
        $help = $help_repository->find($help_id);

        if (!$help) {
            throw $this->createNotFoundException($translator->trans('No help found'));
        }

        $output['status'] = true;
        if(!$help->getMessage()) {
            $output['status'] = false;
        }
        $response = new Response();
        $response->setContent(json_encode($output));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/admin/mail", name="crud_admin_mail_list")
     * @Template()
     */
    public function listMailAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No mail found'));
        }

        $mails = $help_repository->findBy(array("status" => true, "type" => "mail"));

        $id = $request->query->get('id');
        if($id) {
            $mails = $help_repository->findBy(array("id" => $id));
        }

        $output['mails'] = $mails;
        return $output;
    }

    /**
     * @Route("/admin/mail/{mail_id}/update", name="crud_admin_mail_update")
     * @Template()
     */
    public function updateMailAction($mail_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No mail found'));
        }

        // getting the current mail
        $mail = $help_repository->find($mail_id);
        $output['mail'] = $mail;

        if (!$mail) {
            throw $this->createNotFoundException($translator->trans('No mail found'));
        }

        $translations = $trans_repository->findTranslations($mail);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-mail', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('mail-message-en') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $mail->setTranslatableLocale('en');
            $mail->setMessage($post_data['mail-message-en']);
            $mail->setStatus(true);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["mail-message-$locale"])) {
                    $trans_repository = $trans_repository->translate($mail, 'message', $locale, $post_data["mail-message-$locale"]);
                }
            }

            $em->persist($mail);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Help updated with success."));
            return $this->redirectToRoute('crud_admin_mail_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/mail/{mail_id}", name="crud_admin_mail_show")
     * @Template()
     */
    public function showMailAction($mail_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No mail found'));
        }

        // getting the current mail
        $mail = $help_repository->find($mail_id);
        $output['mail'] = $mail;

        if (!$mail) {
            throw $this->createNotFoundException($translator->trans('No mail found'));
        }

        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($mail);
        $output['translations'] = $translations;

        return $output;
    }

    /**
     * @Route("/admin/mail/{mail_id}/check", name="crud_admin_mail_check")
     */
    public function checkMailAction($mail_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No mail found'));
        }

        // getting the current mail
        $mail = $help_repository->find($mail_id);

        if (!$mail) {
            throw $this->createNotFoundException($translator->trans('No mail found'));
        }

        $output['status'] = true;
        if(!$mail->getMessage()) {
            $output['status'] = false;
        }
        $response = new Response();
        $response->setContent(json_encode($output));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/admin/configuration", name="crud_admin_configuration_list")
     * @Template()
     */
    public function listConfigurationAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $currencies = Intl::getCurrencyBundle()->getCurrencyNames();
        $output['currencies'] = $currencies;

        $country_repository = $em->getRepository('Proethos2ModelBundle:Country');
        $countries = $country_repository->findBy(array(), array('name' => 'asc'));
        $output['countries'] = $countries;

        $configuration_repository = $em->getRepository('Proethos2ModelBundle:Configuration');
        $configurations = $configuration_repository->findAll();
        $output['configurations'] = $configurations;

        $country_locale = $configuration_repository->findBy(array('key' => 'country.locale'));
        $country_code   = explode('|', $country_locale[0]->getValue())[0];
        $currency_code  = explode('|', $country_locale[0]->getValue())[1];
        $output['country_code']  = $country_code;
        $output['currency_code'] = $currency_code;

        $country  = $country_repository->findBy(array('code' => $country_code));
        $currency_name = $currencies[$currency_code];
        $output['country_name']  = $country[0]->getName();
        $output['currency_name'] = $currency_name;

        return $output;
    }

    /**
     * @Route("/admin/configuration/{configuration_id}/update", name="crud_admin_configuration_update")
     * @Template()
     */
    public function updateConfigurationAction($configuration_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        // getting the current configuration
        $configuration_repository = $em->getRepository('Proethos2ModelBundle:Configuration');
        $configuration = $configuration_repository->find($configuration_id);
        $output['configuration'] = $configuration;

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $translations = $trans_repository->findTranslations($configuration);
        $output['translations'] = $translations;

        $router = $this->get('router');
        $config_route = $router->generate('crud_admin_configuration_list', array(), true);
        $referer = $request->headers->get('referer');
        $user_role = 'secretary';

        if ( $referer == $config_route ) {
            $user_role = 'administrator';
        }

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array($user_role, $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No help found'));
        }

        if (!$configuration) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-config', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            $configuration->setValue($post_data['configuration-value']);

            if( $request->query->get('form') and 'translate' == $request->query->get('form') ) {
                // checking required files
                foreach(array('configuration-message-en') as $field) {
                    if(!isset($post_data[$field]) or empty($post_data[$field])) {
                        $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                        return $output;
                    }
                }

                $configuration->setTranslatableLocale('en');
                $configuration->setValue($post_data['configuration-message-en']);

                foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                    if(!empty($post_data["configuration-message-$locale"])) {
                        $trans_repository = $trans_repository->translate($configuration, 'value', $locale, $post_data["configuration-message-$locale"]);
                    }
                }
            }

            // user activity log
            $logger = $this->get('monolog.logger.security');
            $logger->info('Settings updated successfully.', array('username' => $user_logged->getUsername()));

            $em->persist($configuration);
            $em->flush();

            if ( $referer == $config_route ) {
                $session->getFlashBag()->add('success', $translator->trans("Configuration updated with success."));
                return $this->redirectToRoute('crud_admin_configuration_list', array(), 301);
            } else {
                $session->getFlashBag()->add('success', $translator->trans("Protocol checklist updated with success."));
                return $this->redirect($referer, 301);
            }
        }

        return $output;
    }

    /**
     * @Route("/committee/protocol-checklist/show", name="crud_committee_protocol_checklist_show")
     * @Template()
     */
    public function showProtocolChecklist()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        $locale = $request->getSession()->get('_locale');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('secretary', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $configuration_repository = $em->getRepository('Proethos2ModelBundle:Configuration');

        $protocol_checklist = $configuration_repository->findOneBy(array('key' => 'protocol.checklist'));
        $translations = $trans_repository->findTranslations($protocol_checklist);
        $text = $translations[$locale];

        if ( $text ) {
            $output['protocol_checklist'] = $text['value'];
        } else {
            $output['protocol_checklist'] = $protocol_checklist->getValue();
        }

        $output['configuration'] = $protocol_checklist;

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/upload-type-extension", name="crud_admin_controlled_list_upload_type_extension_list")
     * @Template()
     */
    public function listControlledListUploadTypeExtensionAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:UploadTypeExtension');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-upload-type-extension', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('extension') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            // upload type extensions blacklist regex
            $blacklist_regex = '/(\.|\/)(php([0-9])?|html|htm|phar|pht|phtm|phtml|sht|shtm|shtml|pwml|inc|asp|aspx|ascx|cfm|cfc|asis|bat|exe|cmd|sh|pl|cgi|386|dll|com|torrent|js|app|jar|pif|vb|vbscript|wsf|cer|csr|jsp|drv|sys|ade|adp|bas|chm|cpl|crt|csh|fxp|hlp|hta|inf|ins|isp|jse|htaccess|htpasswd|ksh|lnk|mdb|mde|mdt|mdw|msc|msi|msp|mst|ops|pcd|prg|reg|scr|sct|shb|shs|url|vbe|vbs|wsc|wsf|wsh)$/i';

            if( preg_match($blacklist_regex, $post_data['extension']) ) {
                $session->getFlashBag()->add('error', $translator->trans("File extension not allowed"));
                return $output;
            }

            if( strlen($post_data['extension']) < 2 or '.' != $post_data['extension']{0} ) {
                $session->getFlashBag()->add('error', $translator->trans("File extension not allowed"));
                return $this->redirectToRoute('crud_admin_controlled_list_upload_type_extension_list', array(), 301);
            }

            $extension = explode('.', $post_data['extension']);
            if( count($extension) > 2 ) {
                $session->getFlashBag()->add('error', $translator->trans("File extension not allowed"));
                return $this->redirectToRoute('crud_admin_controlled_list_upload_type_extension_list', array(), 301);
            }

            $item = new UploadTypeExtension();
            $item->setExtension($post_data['extension']);

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Upload Extension Type created with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_upload_type_extension_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/upload-type-extension/{item_id}", name="crud_admin_controlled_list_upload_type_extension_update")
     * @Template()
     */
    public function updateControlledListUploadTypeExtensionAction($item_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:UploadTypeExtension');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No extension found'));
        }

        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No extension found'));
        }

        $output['item'] = $item;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-upload-type-extension', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('extension') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $this->redirectToRoute('crud_admin_controlled_list_upload_type_extension_list', array(), 301);
                }
            }

            // upload type extensions blacklist regex
            $blacklist_regex = '/(\.|\/)(php([0-9])?|html|htm|phar|pht|phtm|phtml|sht|shtm|shtml|pwml|inc|asp|aspx|ascx|cfm|cfc|asis|bat|exe|cmd|sh|pl|cgi|386|dll|com|torrent|js|app|jar|pif|vb|vbscript|wsf|cer|csr|jsp|drv|sys|ade|adp|bas|chm|cpl|crt|csh|fxp|hlp|hta|inf|ins|isp|jse|htaccess|htpasswd|ksh|lnk|mdb|mde|mdt|mdw|msc|msi|msp|mst|ops|pcd|prg|reg|scr|sct|shb|shs|url|vbe|vbs|wsc|wsf|wsh)$/i';

            if( preg_match($blacklist_regex, $post_data['extension']) ) {
                $session->getFlashBag()->add('error', $translator->trans("File extension not allowed"));
                return $this->redirectToRoute('crud_admin_controlled_list_upload_type_extension_list', array(), 301);
            }

            if( strlen($post_data['extension']) < 2 or '.' != $post_data['extension']{0} ) {
                $session->getFlashBag()->add('error', $translator->trans("File extension not allowed"));
                return $this->redirectToRoute('crud_admin_controlled_list_upload_type_extension_list', array(), 301);
            }

            $extension = explode('.', $post_data['extension']);
            if( count($extension) > 2 ) {
                $session->getFlashBag()->add('error', $translator->trans("File extension not allowed"));
                return $this->redirectToRoute('crud_admin_controlled_list_upload_type_extension_list', array(), 301);
            }

            $item->setExtension($post_data['extension']);

            if(isset($post_data['status']) and $post_data['status'] == "true") {
                $item->setStatus(true);
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Upload Extension Type created with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_upload_type_extension_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/upload-type", name="crud_admin_controlled_list_upload_type_list")
     * @Template()
     */
    public function listControlledListUploadTypeAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:UploadType');
        $extensions_repository = $em->getRepository('Proethos2ModelBundle:UploadTypeExtension');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $items = $item_repository->findAll();
        $output['items'] = $items;

        $extensions = $extensions_repository->findByStatus(true);
        $output['extensions'] = $extensions;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-upload-type', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // var_dump($post_data);die;

            // checking required files
            foreach(array('name', 'extensions') as $field) {

                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item = new UploadType();
            $item->setTranslatableLocale('en');
            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            if(isset($post_data['extensions'])) {
                foreach($post_data['extensions'] as $extension) {
                    $extension = $extensions_repository->find($extension);
                    $item->addExtension($extension);
                }
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Upload Extension Type created with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_upload_type_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/upload-type/{item_id}", name="crud_admin_controlled_list_upload_type_update")
     * @Template()
     */
    public function updateControlledListUploadTypeAction($item_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:UploadType');
        $extensions_repository = $em->getRepository('Proethos2ModelBundle:UploadTypeExtension');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No type found'));
        }

        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No type found'));
        }
        $output['item'] = $item;

        $extensions = $extensions_repository->findByStatus(true);
        $output['extensions'] = $extensions;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-upload-type', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name', 'extensions') as $field) {

                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            foreach($item->getExtensions() as $extension) {
                $item->removeExtension($extension);
            }

            $item->setTranslatableLocale('en');
            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            if(isset($post_data['extensions'])) {
                foreach($post_data['extensions'] as $extension) {
                    $extension = $extensions_repository->find($extension);
                    $item->addExtension($extension);
                }
            }

            if(isset($post_data['status']) and $post_data['status'] == "true") {
                $item->setStatus(true);
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Item updated with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_upload_type_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/recruitment-status", name="crud_admin_controlled_list_recruitment_status_list")
     * @Template()
     */
    public function listControlledListRecruitmentStatusAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:RecruitmentStatus');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-recruitment-status', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name') as $field) {

                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item = new RecruitmentStatus();
            $item->setTranslatableLocale('en');

            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Status created with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_recruitment_status_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/recruitment-status/{item_id}", name="crud_admin_controlled_list_recruitment_status_update")
     * @Template()
     */
    public function updateControlledListRecruitmentStatusAction($item_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:RecruitmentStatus');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }

        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }
        $output['item'] = $item;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-recruitment-status', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item->setTranslatableLocale('en');

            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            if(isset($post_data['status']) and $post_data['status'] == "true") {
                $item->setStatus(true);
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Status created with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_recruitment_status_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/monitoring-action", name="crud_admin_controlled_list_monitoring_action_list")
     * @Template()
     */
    public function listControlledListMonitoringActionAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:MonitoringAction');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-monitoring-action', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name') as $field) {

                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item = new MonitoringAction();
            $item->setTranslatableLocale('en');

            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Item created with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_monitoring_action_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/monitoring-action/{item_id}", name="crud_admin_controlled_list_monitoring_action_update")
     * @Template()
     */
    public function updateControlledListMonitoringActionAction($item_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:MonitoringAction');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }

        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }
        $output['item'] = $item;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-monitoring-action', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item->setTranslatableLocale('en');

            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            if(isset($post_data['status']) and $post_data['status'] == "true") {
                $item->setStatus(true);
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Item updated with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_monitoring_action_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/clinical-trial-name", name="crud_admin_controlled_list_clinical_trial_name_list")
     * @Template()
     */
    public function listControlledListClinicalTrialNameAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:ClinicalTrialName');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-clinical-trial-name', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name') as $field) {

                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item = new ClinicalTrialName();
            $item->setTranslatableLocale('en');

            $item->setName($post_data['code']);
            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Item created with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_clinical_trial_name_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/clinical-trial-name/{item_id}", name="crud_admin_controlled_list_clinical_trial_name_update")
     * @Template()
     */
    public function updateControlledListClinicalTrialNameAction($item_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:ClinicalTrialName');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }
        $output['item'] = $item;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-clinical-trial-name', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item->setTranslatableLocale('en');

            $item->setName($post_data['code']);
            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            if(isset($post_data['status']) and $post_data['status'] == "true") {
                $item->setStatus(true);
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Item updated with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_clinical_trial_name_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/gender", name="crud_admin_controlled_list_gender_list")
     * @Template()
     */
    public function listControlledListGenderAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:Gender');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('add-gender', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name') as $field) {

                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item = new Gender();
            $item->setTranslatableLocale('en');

            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Item created with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_gender_list', array(), 301);
        }

        return $output;
    }

    /**
     * @Route("/admin/controlled-list/gender/{item_id}", name="crud_admin_controlled_list_gender_update")
     * @Template()
     */
    public function updateControlledListGenderAction($item_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $item_repository = $em->getRepository('Proethos2ModelBundle:Gender');
        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');

        $user_logged = $this->get('security.token_storage')->getToken()->getUser();
        
        if (!in_array('administrator', $user_logged->getRolesSlug())) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }

        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }
        $output['item'] = $item;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            $submittedToken = $request->request->get('token');

            if (!$this->isCsrfTokenValid('update-gender', $submittedToken)) {
                throw $this->createNotFoundException($translator->trans('CSRF token not valid'));
            }

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            foreach(array('name') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $item->setTranslatableLocale('en');

            $item->setName($post_data['name']);

            foreach(array('pt_BR', 'es_ES', 'fr_FR') as $locale) {
                if(!empty($post_data["name-$locale"])) {
                    $trans_repository = $trans_repository->translate($item, 'name', $locale, $post_data["name-$locale"]);
                }
            }

            if(isset($post_data['status']) and $post_data['status'] == "true") {
                $item->setStatus(true);
            }

            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Item updated with success."));
            return $this->redirectToRoute('crud_admin_controlled_list_gender_list', array(), 301);
        }

        return $output;
    }
}
