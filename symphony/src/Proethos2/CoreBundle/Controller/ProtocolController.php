<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Proethos2\ModelBundle\Entity\ProtocolComment;

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

            // print '<pre>';
            // var_dump($post_data);
            // die;
            
            if(isset($post_data['team-id'])) {

                $id = $post_data['team-id'];
                $team_user = $user_repository->find($id);
                $submission->addTeam($team_user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($submission);
                $em->flush();

                $session->getFlashBag()->add('success', $translator->trans("User added to team with sucess."));
            }

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

}
