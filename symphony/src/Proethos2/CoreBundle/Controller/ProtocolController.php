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

                $recipients = array($protocol->getOwner());
                foreach($protocol->getMainSubmission()->getTeam() as $team_member) {
                    $recipients[] = $team_member;
                }

                foreach($recipients as $recipient) {
                    $message = \Swift_Message::newInstance()
                    ->setSubject("[proethos2] " . $translator->trans("Your protocol was rejected"))
                    ->setFrom($util->getConfiguration('committee.email'))
                    ->setTo($recipient->getEmail())
                    ->setBody(
                        $translator->trans("Hello!") .
                        "<br>" .
                        "<br>" . $translator->trans("Your protocol was rejected. Access the link below for more details") . ":" .
                        "<br>" .
                        "<br>$url" .
                        "<br>" .
                        "<br>". $translator->trans("Regards") . "," .
                        "<br>" . $translator->trans("Proethos2 Team")
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
                $em->flush();

                // setting new main submission
                $protocol->setMainSubmission($new_submission);

                // setting the Rejected status
                $protocol->setStatus("R");

                // setting protocool history
                $protocol_history = new ProtocolHistory();
                $protocol_history->setProtocol($protocol);
                $protocol_history->setMessage($translator->trans("Protocol was rejected by") ." ". $user . ".");
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
                    $url = $baseurl . $this->generateUrl('home');

                    foreach($user_repository->findAll() as $member) {
                        foreach(array("members-of-committee") as $role) {
                            if(in_array($role, $member->getRolesSlug())) {

                                $message = \Swift_Message::newInstance()
                                ->setSubject("[proethos2] " . $translator->trans("A new protocol needs your analysis."))
                                ->setFrom($util->getConfiguration('committee.email'))
                                ->setTo($member->getEmail())
                                ->setBody(
                                    $translator->trans("Hello!") .
                                    "<br>" .
                                    "<br>" . $translator->trans("A new protocol needs your analysis. Access the link below") . ":" .
                                    "<br>" .
                                    "<br>$url" .
                                    "<br>" .
                                    "<br>". $translator->trans("Regards") . "," .
                                    "<br>" . $translator->trans("Proethos2 Team")
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

                        $message = \Swift_Message::newInstance()
                        ->setSubject("[proethos2] " . $translator->trans("Your protocol was accepted!"))
                        ->setFrom($util->getConfiguration('committee.email'))
                        ->setTo($investigator->getEmail())
                        ->setBody(
                            $translator->trans("Hello!") .
                            "<br>" .
                            "<br>" . $translator->trans("Your protocol was accepted. Access the link below") . ":" .
                            "<br>" .
                            "<br>$url" .
                            "<br>" .
                            "<br>". $translator->trans("Regards") . "," .
                            "<br>" . $translator->trans("Proethos2 Team")
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
                $protocol_history->setMessage($translator->trans("Protocol has been accepeted and investigators notified."));
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

                    $message = \Swift_Message::newInstance()
                    ->setSubject("[proethos2] " . $translator->trans("Your protocol was accepted!"))
                    ->setFrom($util->getConfiguration('committee.email'))
                    ->setTo($investigator->getEmail())
                    ->setBody(
                        $translator->trans("Hello!") .
                        "<br>" .
                        "<br>" . $translator->trans("Your protocol was accepted. Access the link below") . ":" .
                        "<br>" .
                        "<br>$url" .
                        "<br>" .
                        "<br>". $translator->trans("Regards") . "," .
                        "<br>" . $translator->trans("Proethos2 Team")
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
                    $submission_upload->setSubmissionNumber($protocol->getMainSubmission()->getNumber());
                    $submission_upload->setFile($file);

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
                            $url = $baseurl . $this->generateUrl('home');

                            $message = \Swift_Message::newInstance()
                            ->setSubject("[proethos2] " . $translator->trans("You were assigned to review a protocol"))
                            ->setFrom($util->getConfiguration('committee.email'))
                            ->setTo($member->getEmail())
                            ->setBody(
                                $translator->trans("Hello!") .
                                "<br>" .
                                "<br>" . $translator->trans("You were assigned to review a protocol. Please access the link below") . ":" .
                                "<br>" .
                                "<br>$url" .
                                "<br>" .
                                "<br>". $translator->trans("Regards") . "," .
                                "<br>" . $translator->trans("Proethos2 Team")
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

        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

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
            $submission_upload->setSubmissionNumber($protocol->getMainSubmission()->getNumber());
            $submission_upload->setFile($file);

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

            $session->getFlashBag()->add('success', $translator->trans("Protocol was finalized with success!"));
            return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);
        }

        return $output;
    }
}
