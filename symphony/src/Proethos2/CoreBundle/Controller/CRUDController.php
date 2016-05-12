<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Proethos2\ModelBundle\Entity\Meeting;
use Proethos2\ModelBundle\Entity\Faq;


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

        $meetings = $meeting_repository->findAll();
        
        // serach parameter
        $search_query = $request->query->get('q');
        if($search_query) {
            $meetings = $meeting_repository->createQueryBuilder('m')
               ->where('m.subject LIKE :query')
               ->setParameter('query', "%". $search_query ."%")
               ->getQuery()
               ->getResult();
        }
        
        $output['meetings'] = $meetings;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            if(isset($post_data['new-meeting-date'])) {

                
                // checking required fields
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

        // getting the current meeting
        $meeting = $meeting_repository->find($meeting_id);
        $output['meeting'] = $meeting;

        if (!$meeting) {
            throw $this->createNotFoundException($translator->trans('No meeting found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('new-meeting-date', 'new-meeting-subject', 'new-meeting-content') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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

        // getting the current meeting
        $meeting = $meeting_repository->find($meeting_id);
        $output['meeting'] = $meeting;

        if (!$meeting) {
            throw $this->createNotFoundException($translator->trans('No meeting found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('meeting-delete') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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

        $criteria = new \Doctrine\Common\Collections\Criteria();

        // Add a not equals parameter to your criteria
        $criteria->where($criteria->expr()->neq('status', 'D'));

        // Find all from the repository matching your criteria
        $protocols = $protocol_repository->matching($criteria);
        
        // serach  and status parameter
        $status_array = array('S', 'R', 'I', 'E', 'H', "F", "A", "N", "C", "X");
        $search_query = $request->query->get('q');
        $status_query = $request->query->get('status');
        
        if(!empty($status_query))
            $status_array = array($status_query);

        $query = $protocol_repository->createQueryBuilder('p')->join('p.main_submission', 's')
           ->where("s.publicTitle LIKE :query AND p.status IN (:status)")
           ->setParameter('query', "%". $search_query ."%")
           ->setParameter('status', $status_array);

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

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');

        // Find all from the repository matching your criteria
        $protocols = $protocol_repository->findBy(array("owner" => $user));
        
        // serach  and status parameter
        $status_array = array('S', 'R', 'I', 'E', 'H', 'D');
        $search_query = $request->query->get('q');
        $status_query = $request->query->get('status');
        
        if(!empty($status_query))
            $status_array = array($status_query);

        $query = $protocol_repository->createQueryBuilder('p')->join('p.main_submission', 's')
           ->where("s.publicTitle LIKE :query AND p.status IN (:status) AND s.owner = :owner")
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
     * @Route("/committee/faq/{faq_id}", name="crud_committee_faq_update")
     * @Template()
     */
    public function updateFaqAction($faq_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $faq_repository = $em->getRepository('Proethos2ModelBundle:Faq');

        // getting the current faq
        $question = $faq_repository->find($faq_id);
        $output['question'] = $question;
        
        if (!$question) {
            throw $this->createNotFoundException($translator->trans('No faq found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('new-question', 'new-question-answer') as $field) {   
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $question->setQuestion($post_data['new-question']);
            $question->setAnswer($post_data['new-question-answer']);

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
     * @Route("/committee/faq", name="crud_committee_faq_list")
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

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('new-question', 'new-question-answer') as $field) {   
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $question = new Faq();
            $question->setQuestion($post_data['new-question']);
            $question->setAnswer($post_data['new-question-answer']);

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


}
