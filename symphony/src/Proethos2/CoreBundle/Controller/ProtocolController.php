<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Proethos2\ModelBundle\Entity\ProtocolComment;
use Proethos2\ModelBundle\Entity\ProtocolHistory;
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
}
