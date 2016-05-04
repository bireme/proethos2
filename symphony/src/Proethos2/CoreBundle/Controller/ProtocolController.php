<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

use Proethos2\ModelBundle\Entity\ProtocolComment;
use Proethos2\ModelBundle\Entity\ProtocolHistory;
use Proethos2\ModelBundle\Entity\ProtocolRevision;
use Proethos2\ModelBundle\Entity\Submission;

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

        if (!$protocol or $protocol->getStatus() == "D") {
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
     * @Route("/protocol/{protocol_id}/analyse", name="protocol_analyse_protocol")
     * @Template()
     */
    public function analyseProtocolAction($protocol_id)
    {
        
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

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

                // cloning submission
                $new_submission = clone $submission;
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
                return $this->redirectToRoute('submission_new_second_step', array('submission_id' => $new_submission->getId()), 301);
            
            } else {

                // generate the code
                $committee_prefix = $this->container->getParameter('committee.prefix');
                $total_submissions = count($protocol->getSubmission());
                $protocol_code = sprintf('%s.%04d.%02d', $committee_prefix, $protocol->getId(), $total_submissions); 
                $protocol->setCode($protocol_code);

                if($post_data['send-to'] == "comittee") {

                    // setting the Rejected status
                    $protocol->setStatus("I");

                    // setting protocool history
                    $protocol_history = new ProtocolHistory();
                    $protocol_history->setProtocol($protocol);
                    $protocol_history->setMessage($translator->trans("Protocol was send to comittee to initial analysis."));
                    $em->persist($protocol_history);
                    $em->flush();

                    $session->getFlashBag()->add('success', $translator->trans("Protocol updated with success!"));
                    return $this->redirectToRoute('protocol_initial_committee_screening', array('protocol_id' => $protocol->getId()), 301);
                }

                if($post_data['send-to'] == "ethical-revision") {

                    // setting the Rejected status
                    $protocol->setStatus("E");
                    
                    // setting protocool history
                    $protocol_history = new ProtocolHistory();
                    $protocol_history->setProtocol($protocol);
                    $protocol_history->setMessage($translator->trans("Protocol was accepted."));
                    $em->persist($protocol_history);
                    $em->flush();


                }

                $em->persist($protocol);
                $em->flush();
                
                $session->getFlashBag()->add('success', $translator->trans("Protocol updated with success!"));
                return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);
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
        
        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        
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
                $protocol_history->setMessage($translator->trans("Protocol was accepted."));
                $em->persist($protocol_history);
                $em->flush();

                $em->persist($protocol);
                $em->flush();
                
                $session->getFlashBag()->add('success', $translator->trans("Protocol updated with success!"));
                return $this->redirectToRoute('protocol_show_protocol', array('protocol_id' => $protocol->getId()), 301);
            }

            if($post_data['send-to'] == "excempt") {

                // setting the Rejected status
                $protocol->setStatus("F");
                    
                // setting protocool history
                $protocol_history = new ProtocolHistory();
                $protocol_history->setProtocol($protocol);
                $protocol_history->setMessage($translator->trans("Protocol was setted as excempt."));
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
        
        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        // gettings relators members
        $role_member_of_committee = $role_repository->findOneBy(array('slug' => 'member-of-committee'));
        $role_member_ad_hoc = $role_repository->findOneBy(array('slug' => 'member-ad-hoc'));
        
        $output['role_member_of_committee'] = $role_member_of_committee;
        $output['role_member_ad_hoc'] = $role_member_ad_hoc;
        
        $users = $user_repository->findAll();
        $output['users'] = $users;

        if (!$protocol or $protocol->getStatus() != "E") {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            if(isset($post_data['opinion-required']) and !empty($post_data['opinion-required'])) {
                
                $protocol->setOpinionRequired($post_data['opinion-required']);

                $em->persist($protocol);
                $em->flush();
            }

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
                            }

                            $em->persist($revision);
                            $em->flush();

                        }
                    }
                }

                $session->getFlashBag()->add('success', $translator->trans("Member added with with success!"));
                return $this->redirectToRoute('protocol_initial_committee_review', array('protocol_id' => $protocol->getId()), 301);

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
            throw $this->createNotFoundException($translator->trans('You are not abble to edit this protocol'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // only change if is not final revision
            if(!$protocol_revision->getIsFinalRevision()) {

                // checking required files
                foreach(array('decision', 'sugestions') as $field) {
                    if(!isset($post_data[$field]) or empty($post_data[$field])) {
                        $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                        return $output;
                    }
                }

                if($post_data['is-final-revision'] == "true") {
                    $protocol_revision->setIsFinalRevision(true);
                }

                $protocol_revision->setDecision($post_data['decision']);
                $protocol_revision->setSocialValue($post_data['social-value']);
                $protocol_revision->setScientificValidity($post_data['scientific-validity']);
                $protocol_revision->setFairParticipantSelection($post_data['fair-participant-selection']);
                $protocol_revision->setFavorableBalance($post_data['favorable-balance']);
                $protocol_revision->setInformedConsent($post_data['informed-consent']);
                $protocol_revision->setRespectForParticipants($post_data['respect-for-participants']);
                $protocol_revision->setOtherComments($post_data['other-comments']);
                $protocol_revision->setSugestions($post_data['sugestions']);
                
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
}
