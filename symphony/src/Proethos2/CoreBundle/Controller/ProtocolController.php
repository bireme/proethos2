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
use Symfony\Component\HttpFoundation\Response;

use Proethos2\CoreBundle\Util\Util;

use Proethos2\ModelBundle\Entity\ProtocolComment;
use Proethos2\ModelBundle\Entity\ProtocolHistory;
use Proethos2\ModelBundle\Entity\ProtocolRevision;
use Proethos2\ModelBundle\Entity\Submission;
use Proethos2\ModelBundle\Entity\SubmissionUpload;

class ProtocolController extends Controller
{
    /**
     * @Route("/protocol/{protocol_id}", name="protocol_show_protocol")
     * @Template()
     */
    public function showProtocolAction($protocol_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        if (!$protocol) {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // if has new comment
            if(isset($post_data['new-comment-message'])) {

                $user = $this->get('security.token_storage')->getToken()->getUser();

                $comment = new ProtocolComment();
                $comment->setProtocol($protocol);
                $comment->setOwner($user);
                $comment->setMessage($post_data['new-comment-message']);

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("Comment was created with sucess."));
            }
        }

        return $output;
    }
    /**
     * @Route("/protocol/{protocol_id}/comment", name="protocol_new_comment")
     */
    public function newCommentProtocolAction($protocol_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        $util = new Util($this->container, $this->getDoctrine());

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        if (!$protocol) {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        $referer = $request->headers->get('referer');

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            $user = $this->get('security.token_storage')->getToken()->getUser();

            $comment = new ProtocolComment();
            $comment->setProtocol($protocol);
            $comment->setOwner($user);
            $comment->setMessage($post_data['new-comment-message']);

            if(isset($post_data['new-comment-is-confidential']) and $post_data['new-comment-is-confidential'] == "yes") {
                $comment->setIsConfidential(true);
            }

            $em->persist($comment);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Comment was created with sucess."));
        }

        return $this->redirect($referer, 301);
    }

    /**
     * @Route("/protocol/{protocol_id}/analyze", name="protocol_analyze_protocol")
     * @Template()
     */
    public function analyzeProtocolAction($protocol_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $util = new Util($this->container, $this->getDoctrine());

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        $mail_translator = $this->get('translator');
        $mail_translator->setLocale($submission->getLanguage());

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        if (!$protocol or $protocol->getStatus() != "S") {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            if(isset($post_data['is-reject']) and $post_data['is-reject'] == "true") {

                // sending email
                $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));

                $help = $help_repository->find(209);
                $translations = $trans_repository->findTranslations($help);
                $text = $translations[$submission->getLanguage()];
                $body = $text['message'];
                $body = str_replace("%protocol_url%", $url, $body);
                $body = str_replace("\r\n", "<br />", $body);
                $body .= "<br /><br />";

                $recipients = array($protocol->getOwner());
                foreach($protocol->getMainSubmission()->getTeam() as $team_member) {
                    $recipients[] = $team_member;
                }

                foreach($recipients as $recipient) {
                    $message = \Swift_Message::newInstance()
                    ->setSubject("[proethos2] " . $mail_translator->trans("Your protocol was rejected"))
                    ->setFrom($util->getConfiguration('committee.email'))
                    ->setTo($recipient->getEmail())
                    ->setBody(
                        $body
                        ,
                        'text/html'
                    );

                    $send = $this->get('mailer')->send($message);
                }

                if($protocol->getMonitoringAction()) {

                    $protocol_history = new ProtocolHistory();
                    $protocol_history->setProtocol($protocol);
                    $protocol_history->setMessage($translator->trans('Monitoring action was rejected by %user% with this justification "%justify%".',
                        array(
                            '%user%' => $user->getUsername(),
                            '%justify%' => $post_data['reject-reason'],
                        )
                    ));
                    $em->persist($protocol_history);
                    $em->flush();

                    $protocol->setStatus("A");
                    $protocol->setMonitoringAction(NULL);

                    $em->persist($protocol);
                    $em->flush();

                    $session->getFlashBag()->add('success', $translator->trans("Protocol rejected with success!"));
                    return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);
                }

                // cloning submission
                $new_submission = clone $submission;
                $new_submission->setNumber($submission->getNumber() + 1);
                $em->persist($new_submission);

                // cloning translations
                foreach($submission->getTranslations() as $translation) {
                    $new_translation = clone $translation;
                    $new_translation->setOriginalSubmission($new_submission);
                    $new_translation->setNumber($translation->getNumber() + 1);
                    $em->persist($new_translation);

                    $new_submission->addTranlsation($new_translation);
                    $em->persist($new_submission);
                }
                $em->flush();

                // setting new main submission
                $protocol->setMainSubmission($new_submission);

                // setting the Rejected status
                $protocol->setStatus("R");

                // setting protocool history
                $protocol_history = new ProtocolHistory();
                $protocol_history->setProtocol($protocol);
                // $protocol_history->setMessage($translator->trans("Protocol was rejected by") ." ". $user . ".");
                $protocol_history->setMessage($translator->trans('Protocol was rejected by %user% with this justification "%justify%".',
                    array(
                        '%user%' => $user->getUsername(),
                        '%justify%' => $post_data['reject-reason'],
                    )
                ));
                $em->persist($protocol_history);
                $em->flush();

                // setting the reason
                $protocol->setRejectReason($post_data['reject-reason']);

                $em->persist($protocol);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("Protocol rejected with success!"));
                return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);

            } else {

                // generate the code
                $committee_prefix = $util->getConfiguration('committee.prefix');
                $total_submissions = count($protocol->getSubmission());
                $protocol_code = sprintf('%s.%04d.%02d', $committee_prefix, $protocol->getId(), $total_submissions);
                $protocol->setCode($protocol_code);

                if($post_data['send-to'] == "comittee") {

                    // setting the Rejected status
                    $protocol->setStatus("I");

                    // setting protocool history
                    $protocol_history = new ProtocolHistory();
                    $protocol_history->setProtocol($protocol);
                    $protocol_history->setMessage($translator->trans("Protocol was sent to comittee for initial analysis by %user%.", array("%user%" => $user->getUsername())));
                    $em->persist($protocol_history);
                    $em->flush();

                    $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                    // $url = $baseurl . $this->generateUrl('home');
                    $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));

                    $help = $help_repository->find(210);
                    $translations = $trans_repository->findTranslations($help);
                    $text = $translations[$submission->getLanguage()];
                    $body = $text['message'];
                    $body = str_replace("%protocol_url%", $url, $body);
                    $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
                    $body = str_replace("\r\n", "<br />", $body);
                    $body .= "<br /><br />";

                    foreach($user_repository->findAll() as $member) {
                        foreach(array("members-of-committee") as $role) {
                            if(in_array($role, $member->getRolesSlug())) {

                                $message = \Swift_Message::newInstance()
                                ->setSubject("[proethos2] " . $mail_translator->trans("A new protocol needs your analysis."))
                                ->setFrom($util->getConfiguration('committee.email'))
                                ->setTo($member->getEmail())
                                ->setBody(
                                    $body
                                    ,
                                    'text/html'
                                );

                                $send = $this->get('mailer')->send($message);
                            }
                        }
                    }

                    $session->getFlashBag()->add('success', $translator->trans("Protocol updated with success!"));
                    return $this->redirectToRoute('protocol_initial_committee_screening', array('protocol_id' => $protocol->getId()), 301);
                }

                if($post_data['send-to'] == "ethical-revision") {

                    // setting the Rejected status
                    $protocol->setStatus("E");

                    // setting protocool history
                    $protocol_history = new ProtocolHistory();
                    $protocol_history->setProtocol($protocol);
                    $protocol_history->setMessage($translator->trans("Protocol accepted for review by %user% and investigators notified.", array("%user%" => $user->getUsername())));
                    $em->persist($protocol_history);
                    $em->flush();

                    $em->persist($protocol);
                    $em->flush();

                    $investigators = array();
                    $investigators[] = $protocol->getMainSubmission()->getOwner();
                    foreach($protocol->getMainSubmission()->getTeam() as $investigator) {
                        $investigators[] = $investigator;
                    }
                    foreach($investigators as $investigator) {
                        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                        $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));

                        $help = $help_repository->find(211);
                        $translations = $trans_repository->findTranslations($help);
                        $text = $translations[$submission->getLanguage()];
                        $body = $text['message'];
                        $body = str_replace("%protocol_url%", $url, $body);
                        $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
                        $body = str_replace("\r\n", "<br />", $body);
                        $body .= "<br /><br />";

                        $message = \Swift_Message::newInstance()
                        ->setSubject("[proethos2] " . $mail_translator->trans("Your protocol was sent to review!"))
                        ->setFrom($util->getConfiguration('committee.email'))
                        ->setTo($investigator->getEmail())
                        ->setBody(
                            $body
                            ,
                            'text/html'
                        );

                        $send = $this->get('mailer')->send($message);
                    }

                    $session->getFlashBag()->add('success', $translator->trans("Protocol updated with success!"));
                    return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);
                }

                if($post_data['send-to'] == "notification-only") {

                    $protocol->setStatus("A");
                    $protocol->setMonitoringAction(NULL);

                    // setting protocool history
                    $protocol_history = new ProtocolHistory();
                    $protocol_history->setProtocol($protocol);
                    $protocol_history->setMessage($translator->trans("Monitoring action was accepted by %user% as notification only.", array("%user%" => $user->getUsername())));
                    $em->persist($protocol_history);
                    $em->flush();

                    $em->persist($protocol);
                    $em->flush();

                    $session->getFlashBag()->add('success', $translator->trans("Protocol updated with success!"));
                    return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);
                }

            }
        }

        return $output;
    }

    /**
     * @Route("/protocol/{protocol_id}/initial-committee-screening", name="protocol_initial_committee_screening")
     * @Template()
     */
    public function initCommitteeScreeningAction($protocol_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $util = new Util($this->container, $this->getDoctrine());

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $upload_type_repository = $em->getRepository('Proethos2ModelBundle:UploadType');

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        $mail_translator = $this->get('translator');
        $mail_translator->setLocale($submission->getLanguage());

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        if (!$protocol or $protocol->getStatus() != "I") {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // setting the Committee Screening
            $protocol->setCommitteeScreening($post_data['committee-screening']);

            if($post_data['send-to'] == "ethical-revision") {

                // setting the Rejected status
                $protocol->setStatus("E");

                // setting protocool history
                $protocol_history = new ProtocolHistory();
                $protocol_history->setProtocol($protocol);
                $protocol_history->setMessage($translator->trans("Your protocol has been accepted for ethics review. The committee's decision will be informed when the process is finalized."));
                $em->persist($protocol_history);
                $em->flush();

                $em->persist($protocol);
                $em->flush();

                $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));

                if ( $post_data['committee-screening'] ) {
                    $help = $help_repository->find(212);
                    $translations = $trans_repository->findTranslations($help);
                    $text = $translations[$submission->getLanguage()];
                    $body = $text['message'];
                    $body = str_replace("%protocol_url%", $url, $body);
                    $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
                    $body = str_replace("%committee_screening%", $post_data['committee-screening'], $body);
                    $body = str_replace("\r\n", "<br />", $body);
                    $body .= "<br /><br />";
                } else {
                    $help = $help_repository->find(213);
                    $translations = $trans_repository->findTranslations($help);
                    $text = $translations[$submission->getLanguage()];
                    $body = $text['message'];
                    $body = str_replace("%protocol_url%", $url, $body);
                    $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
                    $body = str_replace("\r\n", "<br />", $body);
                    $body .= "<br /><br />";
                }

                $investigators = array();
                $investigators[] = $protocol->getMainSubmission()->getOwner();
                foreach($protocol->getMainSubmission()->getTeam() as $investigator) {
                    $investigators[] = $investigator;
                }
                foreach($investigators as $investigator) {
                    $message = \Swift_Message::newInstance()
                    ->setSubject("[proethos2] " . $mail_translator->trans("Your protocol was sent to review!"))
                    ->setFrom($util->getConfiguration('committee.email'))
                    ->setTo($investigator->getEmail())
                    ->setBody(
                        $body
                        ,
                        'text/html'
                    );

                    $send = $this->get('mailer')->send($message);
                }

                $session->getFlashBag()->add('success', $translator->trans("Protocol updated with success!"));
                return $this->redirectToRoute('protocol_initial_committee_review', array('protocol_id' => $protocol->getId()), 301);
            }

            if($post_data['send-to'] == "exempt") {

                $file = $request->files->get('draft-opinion');
                if(!empty($file)) {

                    // getting the upload type
                    $upload_type = $upload_type_repository->findOneBy(array("slug" => "draft-opinion"));

                    // adding the file uploaded
                    $submission_upload = new SubmissionUpload();
                    $submission_upload->setSubmission($protocol->getMainSubmission());
                    $submission_upload->setUploadType($upload_type);
                    $submission_upload->setUser($user);
                    $submission_upload->setFile($file);
                    $submission_upload->setSubmissionNumber($protocol->getMainSubmission()->getNumber());

                    $attachment = \Swift_Attachment::fromPath($submission_upload->getFilepath());

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($submission_upload);
                    $em->flush();

                    $protocol->getMainSubmission()->addAttachment($submission_upload);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($protocol->getMainSubmission());
                    $em->flush();
                }

                // setting the Rejected status
                $protocol->setStatus("F");

                // setting protocool history
                $protocol_history = new ProtocolHistory();
                $protocol_history->setProtocol($protocol);
                $protocol_history->setMessage($translator->trans("Protocol was concluded as Exempt."));
                $em->persist($protocol_history);
                $em->flush();

                $investigators = array();
                $investigators[] = $protocol->getMainSubmission()->getOwner();
                foreach($protocol->getMainSubmission()->getTeam() as $investigator) {
                    $investigators[] = $investigator;
                }
                foreach($investigators as $investigator) {
                    $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                    $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));

                    $help = $help_repository->find(214);
                    $translations = $trans_repository->findTranslations($help);
                    $text = $translations[$submission->getLanguage()];
                    $body = $text['message'];
                    $body = str_replace("%protocol_url%", $url, $body);
                    $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
                    $body = str_replace("\r\n", "<br />", $body);
                    $body .= "<br /><br />";

                    $message = \Swift_Message::newInstance()
                    ->setSubject("[proethos2] " . $mail_translator->trans("Your protocol was concluded as Exempt."))
                    ->setFrom($util->getConfiguration('committee.email'))
                    ->setTo($investigator->getEmail())
                    ->setBody(
                        $body
                        ,
                        'text/html'
                    );

                    if(!empty($file)) {
                        $message->attach($attachment);
                    }

                    $send = $this->get('mailer')->send($message);
                }

                $session->getFlashBag()->add('success', $translator->trans("Protocol updated with success!"));
                return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);
            }

        }

        return $output;
    }

    /**
     * @Route("/protocol/{protocol_id}/initial-committee-review", name="protocol_initial_committee_review")
     * @Template()
     */
    public function initCommitteeReviewAction($protocol_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');
        $protocol_revision_repository = $em->getRepository('Proethos2ModelBundle:ProtocolRevision');
        $meeting_repository = $em->getRepository('Proethos2ModelBundle:Meeting');

        $util = new Util($this->container, $this->getDoctrine());

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        // gettings reviewers members
        $role_member_of_committee = $role_repository->findOneBy(array('slug' => 'member-of-committee'));
        $role_member_ad_hoc = $role_repository->findOneBy(array('slug' => 'member-ad-hoc'));

        $output['role_member_of_committee'] = $role_member_of_committee;
        $output['role_member_ad_hoc'] = $role_member_ad_hoc;

        // getting users
        $users = $user_repository->findAll();
        $output['users'] = $users;

        // gettings meetings
        $meetings = $meeting_repository->findAll();
        $output['meetings'] = $meetings;

        // getting total of revision with final revision from this protocol
        $final_revisions = $protocol_revision_repository->findBy(array("protocol" => $protocol, "is_final_revision" => true));
        $total_final_revisions = count($final_revisions);
        $output['total_final_revisions'] = $total_final_revisions;

        $mail_translator = $this->get('translator');
        $mail_translator->setLocale($submission->getLanguage());

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        if (!$protocol or $protocol->getStatus() != "E") {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            if(isset($post_data['send-to']) and $post_data['send-to'] == "button-save-and-wait-revisions") {

                // saving opinion required
                if(isset($post_data['opinion-required'])) {
                    $protocol->setOpinionRequired($post_data['opinion-required']);
                    $em->persist($protocol);
                    $em->flush();
                }

                $session->getFlashBag()->add('success', $translator->trans("Options have been saved with success!"));
                return $this->redirectToRoute('protocol_initial_committee_review', array('protocol_id' => $protocol->getId()), 301);
            }

            // check if form used is adding members
            if(isset($post_data['type-of-members'])) {

                foreach(array("select-members-of-committee", "select-members-ad-hoc") as $input_name) {
                    if(isset($post_data[$input_name])) {
                        foreach($post_data['select-members-of-committee'] as $member) {
                            $member = $user_repository->findOneById($member);

                            $revision = $protocol_revision_repository->findOneBy(array('member' => $member, "protocol" => $protocol));
                            if(!$revision) {
                                $revision = new ProtocolRevision();
                                $revision->setMember($member);
                                $revision->setProtocol($protocol);
                                $em->persist($revision);
                                $em->flush();
                            }

                            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                            // $url = $baseurl . $this->generateUrl('home');
                            $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));

                            $help = $help_repository->find(215);
                            $translations = $trans_repository->findTranslations($help);
                            $text = $translations[$submission->getLanguage()];
                            $body = $text['message'];
                            $body = str_replace("%protocol_url%", $url, $body);
                            $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
                            $body = str_replace("\r\n", "<br />", $body);
                            $body .= "<br /><br />";

                            $message = \Swift_Message::newInstance()
                            ->setSubject("[proethos2] " . $mail_translator->trans("You were assigned to review a protocol"))
                            ->setFrom($util->getConfiguration('committee.email'))
                            ->setTo($member->getEmail())
                            ->setBody(
                                $body
                                ,
                                'text/html'
                            );

                            $send = $this->get('mailer')->send($message);

                        }
                    }
                }

                $session->getFlashBag()->add('success', $translator->trans("Members added with success!"));
                return $this->redirectToRoute('protocol_initial_committee_review', array('protocol_id' => $protocol->getId()), 301);
            }

            // if form was to remove a member
            if(isset($post_data['remove-member']) and !empty($post_data['remove-member'])) {

                $revision = $protocol_revision_repository->findOneById($post_data['remove-member']);
                if($revision) {
                    $em->remove($revision);
                    $em->flush();

                    $session->getFlashBag()->add('success', $translator->trans("Member has been removed with success!"));
                    return $this->redirectToRoute('protocol_initial_committee_review', array('protocol_id' => $protocol->getId()), 301);
                }
            }

            if(isset($post_data['send-to']) and $post_data['send-to'] == "button-save-and-send-to-meeting") {

                // checking required fields
                if(!isset($post_data['meeting']) or empty($post_data['meeting'])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field 'meeting' is required."));
                    return $output;
                }

                $meeting = $meeting_repository->find($post_data['meeting']);
                $protocol->setMeeting($meeting);

                // setting the Scheduled status
                $protocol->setStatus("H");
                $protocol->setRevisedIn(new \DateTime());

                $em->persist($protocol);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("Meeting assigned with success!"));
                return $this->redirectToRoute('protocol_end_review', array('protocol_id' => $protocol->getId()), 301);
            }
        }

        return $output;
    }

    /**
     * @Route("/protocol/{protocol_id}/initial-committee-review/revisor", name="protocol_initial_committee_review_revisor")
     * @Template()
     */
    public function initCommitteeReviewRevisorAction($protocol_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');
        $protocol_revision_repository = $em->getRepository('Proethos2ModelBundle:ProtocolRevision');

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        if (!$protocol or $protocol->getStatus() != "E") {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // getting the protocol_revisiion
        $protocol_revision = $protocol_revision_repository->findOneBy(array("protocol" => $protocol, "member" => $user));
        $output['protocol_revision'] = $protocol_revision;

        if (!$protocol_revision) {
            throw $this->createNotFoundException($translator->trans('You cannot edit this protocol'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // only change if is not final revision
            if(!$protocol_revision->getIsFinalRevision()) {

                // checking required files
                foreach(array('decision', 'suggestions') as $field) {
                    if(!isset($post_data[$field]) or empty($post_data[$field])) {
                        $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                        return $output;
                    }
                }

                if($post_data['is-final-revision'] == "true") {
                    $protocol_revision->setIsFinalRevision(true);
                }

                $protocol_revision->setDecision($post_data['decision']);
                $protocol_revision->setSocialValue($post_data['social-value']);
                $protocol_revision->setSscientificValidity($post_data['sscientific-validity']);
                $protocol_revision->setFairParticipantSelection($post_data['fair-participant-selection']);
                $protocol_revision->setFavorableBalance($post_data['favorable-balance']);
                $protocol_revision->setInformedConsent($post_data['informed-consent']);
                $protocol_revision->setRespectForParticipants($post_data['respect-for-participants']);
                $protocol_revision->setOtherComments($post_data['other-comments']);
                $protocol_revision->setSuggestions($post_data['suggestions']);

                $protocol_revision->setAnswered(true);

                $em->persist($protocol_revision);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("Fields answered with success!"));
                return $this->redirectToRoute('protocol_initial_committee_review_revisor', array('protocol_id' => $protocol->getId()), 301);
            }

        }

        return $output;
    }

    /**
     * @Route("/protocol/{protocol_id}/initial-committee-review/show-review/{protocol_revision_id}", name="protocol_initial_committee_review_show_review")
     * @Template()
     */
    public function showReviewAction($protocol_id, $protocol_revision_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_revision_repository = $em->getRepository('Proethos2ModelBundle:ProtocolRevision');

        // getting the current submission
        $protocol_revision = $protocol_revision_repository->find($protocol_revision_id);
        $output['protocol_revision'] = $protocol_revision;

        return $output;
    }

    /**
     * @Route("/protocol/{protocol_id}/end-review", name="protocol_end_review")
     * @Template()
     */
    public function endReviewAction($protocol_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $upload_type_repository = $em->getRepository('Proethos2ModelBundle:UploadType');

        $finish_options = array(
            "A" => $translator->trans("Approved"),
            'N' => $translator->trans('Not approved'),
            'C' => $translator->trans('Conditional approval'),
            'X' => $translator->trans('Expedite approval'),
            'F' => $translator->trans('Exempt'),
        );
        $output['finish_options'] = $finish_options;

        $util = new Util($this->container, $this->getDoctrine());

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        $mail_translator = $this->get('translator');
        $mail_translator->setLocale($submission->getLanguage());

        $trans_repository = $em->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        $help_repository = $em->getRepository('Proethos2ModelBundle:Help');
        // $help = $help_repository->findBy(array("id" => {id}, "type" => "mail"));
        // $translations = $trans_repository->findTranslations($help[0]);

        if (!$protocol or $protocol->getStatus() != "H") {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            $required_fields = array('final-decision');
            foreach($required_fields as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            $file = $request->files->get('draft-opinion');
            if(empty($file)) {
                $session->getFlashBag()->add('error', $translator->trans("You have to upload a decision."));
                return $output;
            }

            // setting the Scheduled status
            $protocol->setStatus($post_data['final-decision']);
            $protocol->setMonitoringAction(NULL);

            // getting the upload type
            $upload_type = $upload_type_repository->findOneBy(array("slug" => "opinion"));

            // adding the file uploaded
            $submission_upload = new SubmissionUpload();
            $submission_upload->setSubmission($protocol->getMainSubmission());
            $submission_upload->setUploadType($upload_type);
            $submission_upload->setUser($user);
            $submission_upload->setFile($file);
            $submission_upload->setSubmissionNumber($protocol->getMainSubmission()->getNumber());

            $attachment = \Swift_Attachment::fromPath($submission_upload->getFilepath());

            if(!empty($post_data['monitoring-period'])) {
                $monitoring_action_next_date = new \DateTime();
                $monitoring_action_next_date->modify('+'. $post_data['monitoring-period'] .' months');
                $protocol->setMonitoringActionNextDate($monitoring_action_next_date);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($submission_upload);
            $em->flush();

            $protocol_history = new ProtocolHistory();
            $protocol_history->setProtocol($protocol);
            $protocol_history->setMessage($translator->trans(
                'Protocol finalized by %user% under option "%option%".',
                array(
                    '%user%' => $user->getUsername(),
                    '%option%' => $finish_options[$post_data['final-decision']],
                )
            ));
            $em->persist($protocol_history);
            $em->flush();

            $protocol->getMainSubmission()->addAttachment($submission_upload);
            $em = $this->getDoctrine()->getManager();
            $em->persist($protocol->getMainSubmission());
            $em->flush();

            $protocol->setDecisionIn(new \DateTime());
            $em->persist($protocol);
            $em->flush();

            $investigators = array();
            $investigators[] = $protocol->getMainSubmission()->getOwner();
            foreach($protocol->getMainSubmission()->getTeam() as $investigator) {
                $investigators[] = $investigator;
            }
            foreach($investigators as $investigator) {
                $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
                $url = $baseurl . $this->generateUrl('protocol_show_protocol', array("protocol_id" => $protocol->getId()));

                $help = $help_repository->find(216);
                $translations = $trans_repository->findTranslations($help);
                $text = $translations[$submission->getLanguage()];
                $body = $text['message'];
                $body = str_replace("%protocol_url%", $url, $body);
                $body = str_replace("%protocol_code%", $protocol->getCode(), $body);
                $body = str_replace("\r\n", "<br />", $body);
                $body .= "<br /><br />";

                $message = \Swift_Message::newInstance()
                ->setSubject("[proethos2] " . $mail_translator->trans("The protocol review was finalized!"))
                ->setFrom($util->getConfiguration('committee.email'))
                ->setTo($investigator->getEmail())
                ->setBody(
                    $body
                    ,
                    'text/html'
                );

                if(!empty($file)) {
                    $message->attach($attachment);
                }

                $send = $this->get('mailer')->send($message);
            }

            $session->getFlashBag()->add('success', $translator->trans("Protocol was finalized with success!"));
            return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);
        }

        return $output;
    }

    /**
     * @Route("/protocol/{protocol_id}/delete", name="protocol_delete")
     * @Template()
     */
    public function deleteAction($protocol_id)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        if (!$protocol or !(in_array('administrator', $user->getRolesSlug()) or $user == $protocol->getOwner())) {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            $required_fields = array('are-you-sure');
            foreach($required_fields as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '%field%' is required.", array("%field%" => $field)));
                    return $output;
                }
            }

            if($post_data['are-you-sure'] == 'yes') {
                $em->remove($protocol);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("Protocol was removed with success!"));
                if(in_array('administrator', $user->getRolesSlug())) {
                    return $this->redirectToRoute('crud_committee_protocol_list', array(), 301);
                }
                return $this->redirectToRoute('crud_investigator_protocol_list', array(), 301);
            }

        }

        return $output;
    }

    /**
     * @Route("/protocol/{protocol_id}/xml/{language_code}", name="protocol_xml")
     * @Template()
     */
    public function XmlOutputAction($protocol_id, $language_code)
    {

        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);

        if (!$protocol or !(in_array('administrator', $user->getRolesSlug()) or $user == $protocol->getOwner())) {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // finding translation
        $submission = NULL;
        if($protocol->getMainSubmission()->getLanguage() == $language_code) {
            $submission = $protocol->getMainSubmission();
        } else {
            foreach($protocol->getMainSubmission()->getTranslations() as $translation) {
                if($translation->getLanguage() == $language_code) {
                    $submission = $translation;
                }
            }
        }

        if(!$submission) {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        $xml = new \SimpleXMLElement('<trials><trial></trial></trials>');

        $utrn = "";
        if($submission->getClinicalTrial()) {
            $trials = $submission->getClinicalTrial();
            foreach($trials as $trial) {
                if($trial->getName()->getSlug() == "universal-trial-number") {
                    $utrn = $trial->getNumber();
                }
            }
        }

        $configuration_repository = $em->getRepository('Proethos2ModelBundle:Configuration');
        $configurations = $configuration_repository->findOneBy(array('key' => 'committee.name'));
        $reg_name = $configurations->getValue();

        // var_dump($xml);
        $main = $xml->addChild('main');
        $main->addChild('trial_id', $protocol->getCode());
        $main->addChild('utrn', $utrn);
        $main->addChild('reg_name', $reg_name);
        $main->addChild('date_registration', $protocol->getCreated()->format("Y-m-d"));
        $main->addChild('primary_sponsor', $submission->getPrimarySponsor());
        $main->addChild('public_title', $submission->getPublicTitle());
        $main->addChild('acronym', $submission->getTitleAcronym());
        $main->addChild('scientific_title', $submission->getScientificTitle());
        $main->addChild('scientific_acronym', $submission->getTitleAcronym());
        $main->addChild('date_enrolment', $protocol->getDateInformed()->format("Y-m-d"));
        $main->addChild('type_enrolment', "actual");
        $main->addChild('target_size', $submission->getSampleSize());
        $main->addChild('recruitment_status', $submission->getRecruitmentStatus()->getName());
        $main->addChild('url', $baseurl . $this->generateUrl('protocol_show_protocol', array('protocol_id' => $protocol->getId())));
        $main->addChild('study_type', "");
        $main->addChild('study_design', $submission->getStudyDesign());
        $main->addChild('phase', "N/A");
        $main->addChild('hc_freetext', $submission->getHealthCondition());
        $main->addChild('i_freetext', $submission->getInterventions());

        $contacts = $main->addChild('contacts');
        $contact = $contacts->addChild('contact');
        $contact->addChild('type', "");
        $contact->addChild('address', "");
        $contact->addChild('city', "");
        $contact->addChild('zip', "");
        $contact->addChild('telephone', "");
        $contact->addChild('email', $submission->getOwner()->getEmail());
        $contact->addChild('affiliation', "");

        if($submission->getOwner()->getCountry()) {
            $contact->addChild('country1', $submission->getOwner()->getCountry()->getName());
        }

        $name = explode(" ", $submission->getOwner()->getName());
        $contact->addChild('firstname', $name[0]);
        if(count($name) > 1) {
            $contact->addChild('middlename', $name[1]);
        }
        if(count($name) > 2) {
            $lastname = str_replace($name[0], "", $submission->getOwner()->getName());
            $lastname = str_replace($name[1], "", $lastname);
            $lastname = trim($lastname);
            $contact->addChild('lastname', $lastname);
        }

        // adicionando agora todo o time
        foreach($submission->getTeam() as $team) {
            $contact = $contacts->addChild('contact');
            $contact->addChild('type', "");
            $contact->addChild('address', "");
            $contact->addChild('city', "");
            $contact->addChild('zip', "");
            $contact->addChild('telephone', "");
            $contact->addChild('email', $team->getEmail());
            $contact->addChild('affiliation', "");

            if($team->getCountry()) {
                $contact->addAttribute('country1', $team->getCountry()->getName());
            }

            $name = explode(" ", $team->getName());
            $contact->addChild('firstname', $name[0]);
            if(count($name) > 1) {
                $contact->addChild('middlename', $name[1]);
            }
            if(count($name) > 2) {
                $lastname = str_replace($name[0], "", $team->getName());
                $lastname = str_replace($name[1], "", $lastname);
                $lastname = trim($lastname);
                $contact->addChild('lastname', $lastname);
            }
        }

        $criterias = $main->addChild('criterias');
        $criteria = $criterias->addChild('criteria');
        $criteria->addChild('inclusion_criteria', $submission->getInclusionCriteria());
        $criteria->addChild('agemin', $submission->getMinimumAge() . "Y");
        $criteria->addChild('agemax', $submission->getMaximumAge() . "Y");
        $criteria->addChild('gender', substr($submission->getGender()->getName(), 0, 1));
        $criteria->addChild('exclusion_criteria', $submission->getExclusionCriteria());

        $primary_outcome = $main->addChild('primary_outcome');
        $primary_outcome->addChild('prim_outcome', $submission->getPrimaryOutcome());

        $primary_sponsor = $main->addChild('primary_sponsor');
        $primary_sponsor->addChild('sponsor_name', $submission->getPrimarySponsor());

        $secondary_outcome = $main->addChild('secondary_outcome');
        $secondary_outcome->addChild('sec_outcome', $submission->getSecondaryOutcome());

        $secondary_sponsor = $main->addChild('secondary_sponsor');
        $secondary_sponsor->addChild('sponsor_name', $submission->getSecondarySponsor());

        return new Response(
            $xml->asXML(),
            200,
            array(
                'Content-Type'          => 'application/xml'
            )
        );
    }
}
