<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class IndexController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $output = array();
        $request = $this->getRequest();
        $session = $request->getSession();
        $translator = $this->get('translator');
        $em = $this->getDoctrine()->getManager();

        $protocol_repository = $em->getRepository('Proethos2ModelBundle:Protocol');
        $protocol_revision_repository = $em->getRepository('Proethos2ModelBundle:ProtocolRevision');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $revisions = array();
        foreach($protocol_revision_repository->findBy(array("member" => $user)) as $revision) {
            if($revision->getProtocol()->getStatus() == 'E') {
                $revisions[] = $revision;
            }
        }
        $output['revisions'] = $revisions;
        
        $submissions = array();
        foreach($protocol_repository->findBy(array("owner" => $user)) as $submission) {
            if($submission->getStatus() == 'D') {
                $submissions[] = $submission;
            }
        }
        $output['submissions'] = $submissions;
        

        return $output;
    }
}
