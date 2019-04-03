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
use Doctrine\Common\Collections\ArrayCollection;

use Proethos2\CoreBundle\Util\Util;

use Proethos2\ModelBundle\Entity\MonitoringAction;
use Proethos2\ModelBundle\Entity\ProtocolHistory;
use Proethos2\ModelBundle\Entity\Submission;
use Proethos2\ModelBundle\Entity\SubmissionUpload;


class MonitoringController extends Controller
{
    /**
     * @Route("/protocol/{protocol_id}/monitoring/", name="protocol_new_monitoring")
     * @Template()
     */
    public function monitoringCreateAction($protocol_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $util = new Util($this->container, $this->getDoctrine());

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $monitoring_action_repository = $em->getRepository('Proethos2ModelBundle:MonitoringAction');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        if (!$protocol) {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        $monitoring_actions = $monitoring_action_repository->findByStatus(true);
        $output['monitoring_actions'] = $monitoring_actions;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            if(!$protocol->getMainSubmission()->isOwner($user)) {
                throw $this->createNotFoundException($translator->trans('You don\'t have access to do this'));
            }

            // checking required files
            foreach(array('monitoring-action') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $monitoring_action = $monitoring_action_repository->find($post_data['monitoring-action']);

            if($monitoring_action->getSlug() == 'submit-an-amendment') {

                $protocol->setMonitoringAction($monitoring_action);
                $em->persist($protocol);
                $em->flush();

                // cloning submission
                $new_submission = clone $submission;
                $new_submission->setNumber($submission->getNumber() + 1);
                $em->persist($new_submission);
                $em->flush();

                // setting new main submission
                $protocol->setMainSubmission($new_submission);

                // setting the Rejected status
                $protocol->setStatus("D");

                // setting protocool history
                $protocol_history = new ProtocolHistory();
                $protocol_history->setProtocol($protocol);
                $protocol_history->setMessage($translator->trans("New amendment submited by") ." ". $user . ".");
                $em->persist($protocol_history);
                $em->flush();

                // $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                // $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));
                //
                // $recipients = array();
                // foreach($user_repository->findAll() as $secretary) {
                //     if(in_array("secretary", $secretary->getRolesSlug())) {
                //         $recipients[] = $secretary;
                //     }
                // }
                //
                // foreach($recipients as $recipient) {
                //     $message = \Swift_Message::newInstance()
                //     ->setSubject("[proethos2] " . $translator->trans("A new monitoring action has been submitted."))
                //     ->setFrom($util->getConfiguration('committee.email'))
                //     ->setTo($recipient->getEmail())
                //     ->setBody(
                //         $translator->trans("Hello!") .
                //         "<br />" .
                //         "<br />" . $translator->trans("A new monitoring action has been submitted. Access the link below for more details") . ":" .
                //         "<br />" .
                //         "<br />$url" .
                //         "<br />" .
                //         "<br />" . $translator->trans("Sincerely") . "," .
                //         "<br />" . $translator->trans("PAHOERC Secretariat") .
                //         "<br />" . $translator->trans("PAHOERC@paho.org")
                //         ,
                //         'text/html'
                //     );
                //
                //     $send = $this->get('mailer')->send($message);
                // }
                //
                // $session->getFlashBag()->add('success', $translator->trans("Amendment submitted with success!"));
                return $this->redirectToRoute('submission_new_second_step', array('submission_id' => $new_submission->getId()), 301);

            } else {

                return $this->redirectToRoute(
                    'protocol_new_monitoring_that_not_amendment',
                    array('protocol_id' => $protocol->getId(), 'monitoring_action_id' => $monitoring_action->getId()),
                    301
                );
            }
        }

        return $output;
    }

        /**
     * @Route("/protocol/{protocol_id}/monitoring/{monitoring_action_id}", name="protocol_new_monitoring_that_not_amendment")
     * @Template()
     */
    public function monitoringCreateThatNotAmendmentAction($protocol_id, $monitoring_action_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $util = new Util($this->container, $this->getDoctrine());

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $monitoring_action_repository = $em->getRepository('Proethos2ModelBundle:MonitoringAction');
        $upload_type_repository = $em->getRepository('Proethos2ModelBundle:UploadType');
        $submission_upload_repository = $em->getRepository('Proethos2ModelBundle:SubmissionUpload');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $output['protocol'] = $protocol;

        if (!$protocol) {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        if(!$protocol->getMainSubmission()->isOwner($user)) {
            throw $this->createNotFoundException($translator->trans('You don\'t have access to do this'));
        }

        $monitoring_action = $monitoring_action_repository->find($monitoring_action_id);
        $output['monitoring_action'] = $monitoring_action;

        $submission = $protocol->getMainSubmission();
        $output['submission'] = $submission;

        $upload_types = $upload_type_repository->findByStatus(true);
        $output['upload_types'] = $upload_types;

        $mail_translator = $this->get('translator');
        $mail_translator->setLocale($submission->getLanguage());

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        if (!$monitoring_action) {
            throw $this->createNotFoundException($translator->trans('Monitoring action does not exist'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            $file = $request->files->get('new-atachment-file');
            if(!empty($file)) {

                if(!isset($post_data['new-atachment-type']) or empty($post_data['new-atachment-type'])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field 'new-atachment-type' is required."));
                    return $output;
                }

                $upload_type = $upload_type_repository->find($post_data['new-atachment-type']);
                if (!$upload_type) {
                    throw $this->createNotFoundException($translator->trans('No upload type found'));
                    return $output;
                }

                $submission_upload = new SubmissionUpload();
                $submission_upload->setSubmission($submission);
                $submission_upload->setUploadType($upload_type);
                $submission_upload->setUser($user);
                $submission_upload->setFile($file);
                $submission_upload->setSubmissionNumber($submission->getNumber());
                $submission_upload->setIsMonitoringAction(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($submission_upload);
                $em->flush();

                $submission->addAttachment($submission_upload);
                $em = $this->getDoctrine()->getManager();
                $em->persist($submission);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("File uploaded with sucess."));
                return $this->redirectToRoute('protocol_new_monitoring_that_not_amendment',
                    array(
                        'protocol_id' => $protocol_id,
                        'monitoring_action_id' => $monitoring_action_id,
                    ), 301);
            }

            if(isset($post_data['delete-attachment-id']) and !empty($post_data['delete-attachment-id'])) {

                $submission_upload = $submission_upload_repository->find($post_data['delete-attachment-id']);
                if($submission_upload) {
                    $em->remove($submission_upload);
                    $em->flush();
                    $session->getFlashBag()->add('success', $translator->trans("File removed with sucess."));
                    return $output;
                }
            }

            // checking required files
            foreach(array('monitoring-action', 'justification') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            foreach($protocol->getMainSubmission()->getAttachments() as $submission_upload) {
                $submission_upload->setIsMonitoringAction(false);
                $em->persist($submission_upload);
                $em->flush();
            }

            $protocol->setMonitoringAction($monitoring_action);
            $em->persist($protocol);
            $em->flush();

            // setting the Rejected status
            $protocol->setStatus("S");
            $protocol->setUpdatedIn(new \DateTime());

            // setting protocool history
            $message = $translator->trans("New amendment submited by");
            $message .= ' "' . $user . '" ';
            $message .= $translator->trans("with this justification:");
            $message .= ' "' . $post_data['justification'] . '"';

            $protocol_history = new ProtocolHistory();
            $protocol_history->setProtocol($protocol);
            $protocol_history->setMessage($message);
            $em->persist($protocol_history);
            $em->flush();

            // sending email
            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
            $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));

            $help = $help_repository->find(217);
            $translations = $trans_repository->findTranslations($help);
            $text = $translations[$submission->getLanguage()];
            $body = $text['message'];
            $body = str_replace("%protocol_url%", $url, $body);
            $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
            $body = str_replace("\r\n", "<br />", $body);
            $body .= "<br /><br />";

            $recipients = array();
            foreach($user_repository->findAll() as $secretary) {
                if(in_array("secretary", $secretary->getRolesSlug())) {
                    $recipients[] = $secretary;
                }
            }

            foreach($recipients as $recipient) {
                $message = \Swift_Message::newInstance()
                ->setSubject("[proethos2] " . $mail_translator->trans("A new monitoring action has been submitted."))
                ->setFrom($util->getConfiguration('committee.email'))
                ->setTo($recipient->getEmail())
                ->setBody(
                    $body
                    ,
                    'text/html'
                );

                $send = $this->get('mailer')->send($message);
            }


            $session->getFlashBag()->add('success', $translator->trans("Amendment submitted with success!"));
            return $this->redirectToRoute('crud_investigator_protocol_list', array(), 301);
        }

        return $output;
    }
}
