<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Proethos2\ModelBundle\Entity\Submission;
use Proethos2\ModelBundle\Entity\Protocol;



class NewSubmissionController extends Controller
{
    /**
     * @Route("/submission/new/first", name="submission_new_first_step")
     * @Template()
     */
    public function FirstStepAction(Request $request)
    {
        $output = array();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();
            
            // checking required files
            foreach(array('cientific_title', 'public_title', 'is_clinical_trial') as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                }
            }

            $protocol = new Protocol();
            $protocol->setOwner($user);
            $em->persist($protocol);
            $em->flush();

            $submission = new Submission();
            $submission->setIsClinicalTrial(($post_data['is_clinical_trial'] == 'yes') ? true : false);
            $submission->setPublicTitle($post_data['public_title']);
            $submission->setCientificTitle($post_data['cientific_title']);
            $submission->setTitleAcronyms($post_data['title_acronyms']);
            $submission->setProtocol($protocol);
            $submission->setOwner($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($submission);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Submission started with success."));
            return $this->redirectToRoute('submission_new_second_step', array('submission_id' => $submission->getId()), 301);
        }
        
        return array();    
    }

    /**
     * @Route("/submission/new/{submission_id}/second", name="submission_new_second_step")
     * @Template()
     */
    public function SecondStepAction($submission_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();
        
        $submission_repository = $em->getRepository('Proethos2ModelBundle:Submission');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        // getting the current submission
        $submission = $submission_repository->find($submission_id);

        if (!$submission) {
            throw $this->createNotFoundException($translator->trans('No submission found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            // checking required files
            $required_fields = array('abstract', 'keywords', 'introduction', 'justify', 'goals');
            foreach($required_fields as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                }
            }
            
            // removing all team to readd
            foreach($submission->getTeam() as $team_user) {
                $submission->removeTeam($team_user);
            }

            // readd
            if(isset($post_data['team_user'])) {
                foreach($post_data['team_user'] as $team_user) {
                    $team_user = $user_repository->find($team_user);
                    $submission->addTeam($team_user);
                }
            }

            // adding fields to model
            $submission->setAbstract($post_data['abstract']);
            $submission->setKeywords($post_data['keywords']);
            $submission->setIntroduction($post_data['introduction']);
            $submission->setJustification($post_data['justify']);
            $submission->setGoals($post_data['goals']);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($submission);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Second step saved with sucess."));
            return $this->redirectToRoute('submission_new_third_step', array('submission_id' => $submission->getId()), 301);
        }
        
        return array(
            'submission' => $submission,
        );    
    }

    /**
     * @Route("/submission/new/{submission_id}/third", name="submission_new_third_step")
     * @Template()
     */
    public function ThirdStepAction($submission_id)
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $submission_repository = $em->getRepository('Proethos2ModelBundle:Submission');
        $user_repository = $em->getRepository('Proethos2ModelBundle:User');

        // getting the current submission
        $submission = $submission_repository->find($submission_id);

        if (!$submission) {
            throw $this->createNotFoundException($translator->trans('No submission found'));
        }

        // checking if was a post request
        if($this->getRequest()->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();


            // checking required files
            $required_fields = array('study-design', 'gender', 'sample-size', 'minimum-age', 'maximum-age', 'inclusion-criteria', 
                'exclusion-criteria', 'recruitment-init-date', 'recruitment-status');
            foreach($required_fields as $field) {
                if(!isset($post_data[$field]) or empty($post_data[$field])) {
                    $session->getFlashBag()->add('error', $translator->trans("Field '$field' is required."));
                }
            }

            // adding fields to model
            $submission->setStudyDesign($post_data['study-design']);
            $submission->setHealthCondition($post_data['health-condition']);
            $submission->setGender($post_data['gender']);
            $submission->setSampleSize($post_data['sample-size']);
            $submission->setMinimumAge($post_data['minimum-age']);
            $submission->setMaximumAge($post_data['maximum-age']);
            $submission->setInclusionCriteria($post_data['inclusion-criteria']);
            $submission->setExclusionCriteria($post_data['exclusion-criteria']);
            $submission->setRecruitmentInitDate(new \DateTime($post_data['recruitment-init-date']));
            $submission->setRecruitmentStatus($post_data['recruitment-status']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($submission);
            $em->flush();

            $session->getFlashBag()->add('success', $translator->trans("Second step saved with sucess."));
            
            // print "<pre>";
            // var_dump($post_data);
            // print "\n\n\n\n";
            // var_dump(array_keys($post_data));
            // die;
        }

        return array(
            'submission' => $submission,
        ); 
    }

}
