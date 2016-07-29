<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Proethos2\CoreBundle\Util\Util;

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

        // getting the current meeting
        $meeting = $meeting_repository->find($meeting_id);
        $output['meeting'] = $meeting;

        if (!$meeting) {
            throw $this->createNotFoundException($translator->trans('No meeting found'));
        }

        $protocols = $protocol_repository->findBy(array('meeting' => $meeting));
        $output['protocols'] = $protocols;

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

        // serach  and status parameter
        $status_array = array('S', 'R', 'I', 'E', 'H', "F", "A", "N", "C", "X");
        $search_query = $request->query->get('q');
        $status_query = $request->query->get('status');
        
        if(!empty($status_query))
            $status_array = array($status_query);

        $query = $protocol_repository->createQueryBuilder('p')->join('p.main_submission', 's')
           ->where("s.publicTitle LIKE :query AND p.status IN (:status)")
           ->orderBy("p.created", 'DESC')
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

        // serach  and status parameter
        $status_array = array('D', 'S', 'R', 'I', 'E', 'H', 'F', 'A', 'N', 'C', 'X');
        $search_query = $request->query->get('q');
        $status_query = $request->query->get('status');
        
        if(!empty($status_query))
            $status_array = array($status_query);

        $query = $protocol_repository->createQueryBuilder('p')
           ->join('p.main_submission', 's')
           ->leftJoin('s.team', 't')
           ->where("s.publicTitle LIKE :query AND p.status IN (:status) AND (s.owner = :owner OR t = :owner)")
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
            
            // checking required files
            foreach(array('question-delete') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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

            // getting post data
            $post_data = $request->request->all();
            $file = $request->files->get('file');

            if(empty($file)) {
                $session->getFlashBag()->add('error', $translator->trans("Field 'file' is required."));
                return $output;
            }

            // checking required fields
            foreach(array('title', 'roles') as $field) {   
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('title',) as $field) {   
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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

        // getting the current document
        $document = $document_repository->find($document_id);
        $output['document'] = $document;
        
        if (!$document) {
            throw $this->createNotFoundException($translator->trans('No document found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('delete') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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

        $users = $user_repository->findAll();
        
        // serach parameter
        $search_query = $request->query->get('q');
        if($search_query) {
            $users = $user_repository->createQueryBuilder('m')
               ->where('m.name LIKE :query')
               ->setParameter('query', "%". $search_query ."%")
               ->getQuery()
               ->getResult();
        }
        
        $output['users'] = $users;
        
        $roles = $role_repository->findAll();
        $output['roles'] = $roles;
        
        $countries = $country_repository->findAll();
        $output['countries'] = $countries;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required fields
            foreach(array('name', 'username', 'email', 'country', ) as $field) {   
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
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

            $message = \Swift_Message::newInstance()
            ->setSubject("[proethos2] " . $translator->trans("Set your password"))
            ->setFrom($util->getConfiguration('committee.email'))
            ->setTo($post_data['email'])
            ->setBody(
                $translator->trans("Hello! You was registered in Proethos2 platform. Please set your password:") .
                "<br>" .
                "<br>" . $translator->trans("Access the link below") . ":" .
                "<br>" .
                "<br>$url" .
                "<br>" .
                "<br>". $translator->trans("Regards") . "," .
                "<br>" . $translator->trans("Proethos2 Team")
                ,   
                'text/html'
            );
            
            $send = $this->get('mailer')->send($message);
            

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

        $country_repository = $em->getRepository('Proethos2ModelBundle:Country');

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $output['user'] = $user;

        $countries = $country_repository->findAll();
        $output['countries'] = $countries;
        
        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('name', 'country', ) as $field) {   
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $country = $country_repository->find($post_data['country']);

            $user->setCountry($country);
            $user->setName($post_data['name']);
            $user->setInstitution($post_data['institution']);

            $em->persist($user);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("User updated with success."));
            return $this->redirectToRoute('home', array(), 301);
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

        $user_repository = $em->getRepository('Proethos2ModelBundle:User');
        $role_repository = $em->getRepository('Proethos2ModelBundle:Role');
        $country_repository = $em->getRepository('Proethos2ModelBundle:Country');

        // getting the current user
        $user = $user_repository->find($user_id);
        $output['user'] = $user;
        
        if (!$user) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }
        
        $roles = $role_repository->findAll();
        $output['roles'] = $roles;
        
        $countries = $country_repository->findAll();
        $output['countries'] = $countries;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // checking required fields
            foreach(array('name', 'country', ) as $field) {   
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $country = $country_repository->find($post_data['country']);

            $user->setCountry($country);
            $user->setName($post_data['name']);
            $user->setInstitution($post_data['institution']);

            $user->setIsActive(false);
            if(isset($post_data['status'])) {
                $user->setIsActive(true);
            }
            
            $em->persist($user);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("User updated with success."));
            return $this->redirectToRoute('crud_committee_user_list', array(), 301);
            
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
        $country_repository = $em->getRepository('Proethos2ModelBundle:Country');

        // getting the current user
        $user = $user_repository->find($user_id);
        $output['user'] = $user;
        
        if (!$user) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }
        
        $roles = $role_repository->findAll();
        $output['roles'] = $roles;
        
        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            foreach($roles as $role) {
                $user->removeProethos2Role($role);

                if(in_array($role->getSlug(), array_keys($post_data))) {
                    $user->addProethos2Role($role);
                }
            }

            // var_dump($post_data);die;
            $em->persist($user);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("User updated with success."));
            return $this->redirectToRoute('crud_committee_user_list', array(), 301);
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
        
        // getting the current user
        $user = $user_repository->find($user_id);
        $output['user'] = $user;
        
        if (!$user) {
            throw $this->createNotFoundException($translator->trans('No user found'));
        }
        
        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('delete') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $em->remove($user);
            $em->flush();

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

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name', 'email', 'subject', 'message') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $message = \Swift_Message::newInstance()
            ->setSubject("[proethos2] " . $translator->trans("Message from plataform."))
            ->setFrom($output['committee_email'])
            ->setTo($output['committee_email'])
            ->setBody(
                $translator->trans("Hello! A message was sent to proethos2 administrator from plataform.") .
                "<br>" .
                "<br><b>User</b>: " . $user->getUsername() . " (" . $user->getEmail() . ")" . 
                "<br><b>Subject</b>: " . $post_data['subject'] . 
                "<br><b>Message</b>:<br>" . 
                nl2br($post_data['message'])
                ,
                'text/html'
            );
            
            $send = $this->get('mailer')->send($message);
            $session->getFlashBag()->add('success', $translator->trans("Message send to administrators."));
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
        
        $helps = $help_repository->findBy(array("status" => true));
        
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

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('help-message-en') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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

        $configuration_repository = $em->getRepository('Proethos2ModelBundle:Configuration');
        
        $configurations = $configuration_repository->findAll();
        
        $output['configurations'] = $configurations;
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

        $configuration_repository = $em->getRepository('Proethos2ModelBundle:Configuration');
        
        // getting the current configuration
        $configuration = $configuration_repository->find($configuration_id);

        if (!$configuration) {
            throw $this->createNotFoundException($translator->trans('No configuration found'));
        }
        $output['configuration'] = $configuration;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('configuration-value') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $configuration->setValue($post_data['configuration-value']);
            
            $em->persist($configuration);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Configuration updated with success."));
            return $this->redirectToRoute('crud_admin_configuration_list', array(), 301);
        }
        
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
        
        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('extension') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $item = new UploadTypeExtension();
            $item->setExtension($post_data['extension']);
            
            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Upload Type Extension created with success."));
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
        
        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No extension found'));
        }
        $output['item'] = $item;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('extension') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                    return $output;
                }
            }

            $item->setExtension($post_data['extension']);
            if(isset($post_data['status']) and $post_data['status'] == "true") {
                $item->setStatus(true);
            }
            
            $em->persist($item);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Upload Type Extension created with success."));
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
        
        $items = $item_repository->findAll();
        $output['items'] = $items;
        
        $extensions = $extensions_repository->findByStatus(true);
        $output['extensions'] = $extensions;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // var_dump($post_data);die;
            
            // checking required files
            foreach(array('name', 'extensions') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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

            $session->getFlashBag()->add('success', $translator->trans("Upload Type Extension created with success."));
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

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name', 'extensions') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
        
        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
        
        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }
        $output['item'] = $item;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
        
        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
        
        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }
        $output['item'] = $item;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
        
        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
        
        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }
        $output['item'] = $item;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
        
        $items = $item_repository->findAll();
        $output['items'] = $items;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name') as $field) {
                
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
        
        $item = $item_repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException($translator->trans('No item found'));
        }
        $output['item'] = $item;

        $translations = $trans_repository->findTranslations($item);
        $output['translations'] = $translations;

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('name') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
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
