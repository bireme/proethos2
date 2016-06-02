<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Collections\ArrayCollection;

use Proethos2\ModelBundle\Entity\MonitoringAction;
use Proethos2\ModelBundle\Entity\ProtocolHistory;
use Proethos2\ModelBundle\Entity\Submission;


class MonitoringController extends Controller
{
    /**
     * @Route("/protocol/{protocol_id}/monitoring/", name="protocol_new_monitoring")
     * @Template()
     */
    public function MonitoringCreateAction($protocol_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $monitoring_action_repository = $em->getRepository('Proethos2ModelBundle:MonitoringAction');
        
        // getting the current submission
        $protocol = $protocol_repository->find($protocol_id);
        $submission = $protocol->getMainSubmission();
        $output['protocol'] = $protocol;

        if (!$protocol) {
            throw $this->createNotFoundException($translator->trans('No protocol found'));
        }

        $monitoring_actions = $monitoring_action_repository->findAll();
        $output['monitoring_actions'] = $monitoring_actions;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            if(!$protocol->getMainSubmission()->isOwner($user)) {
                throw $this->createNotFoundException($translator->trans('You dont have access to do this'));
            }

            // checking required files
            foreach(array('monitoring-action') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $monitoring_action = $monitoring_action_repository->find($post_data['monitoring-action']);

            $protocol->setMonitoringAction($monitoring_action);
            
            $em->persist($protocol);
            $em->flush();            

            if($monitoring_action->getSlug() == 'submit-an-amendment') {

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
                
                $session->getFlashBag()->add('success', $translator->trans("Ammendment submitted with success!"));
                return $this->redirectToRoute('submission_new_second_step', array('submission_id' => $new_submission->getId()), 301);
            
            } else {

            }


        }
        
        return $output;
    }
}
