<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Proethos2\ModelBundle\Entity\Meeting;


class CRUDController extends Controller
{
    /**
     * @Route("/committee/meeting", name="crud_committee_meeting")
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

        $meetings = $meeting_repository->findAll();
        $output['meetings'] = $meetings;
        
        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            if(isset($post_data['new-meeting-date'])) {

                
                // checking required files
                foreach(array('new-meeting-date', 'new-meeting-subject', 'new-meeting-content') as $field) {
                    
                    if(!isset($post_data[$field]) or empty($post_data[$field])) {
                        $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                        return $output;
                    }
                }

                // var_dump($post_data);die;

                $meeting = new Meeting();
                $meeting->setDate(new \DateTime($post_data['new-meeting-date']));
                $meeting->setSubject($post_data['new-meeting-subject']);
                $meeting->setContent($post_data['new-meeting-content']);

                $em->persist($meeting);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("Meeting created with success."));
                return $this->redirectToRoute('crud_committee_meeting', array(), 301);
            }

        }

        return $output;
    }
}
