<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $meeting_repository = $em->getRepository('Proethos2ModelBundle:Meeting');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $revisions = array();
        foreach($protocol_revision_repository->findBy(array("member" => $user)) as $revision) {
            if($revision->getProtocol()->getStatus() == 'E') {
                $revisions[] = $revision->getProtocol();
            }
        }
        foreach($protocol_repository->findBy(array("status" => "I")) as $protocol) {
            $revisions[] = $protocol;
        }
        $output['revisions'] = $revisions;
        
        $submissions = array();
        foreach($protocol_repository->findBy(array("owner" => $user)) as $submission) {
            $submissions[] = $submission;
        }
        $output['submissions'] = $submissions;

        $now = new \DateTime();
        $twoMonths = new \DateTime();
        $twoMonths = $twoMonths->add(new \DateInterval("P60D"));
        
        $qb = $meeting_repository->createQueryBuilder('m');
        $query = $qb->add('where', $qb->expr()->between(
                'm.date',
                ':from',
                ':to'
            )
        )
            ->setParameters(array('from' => $now, 'to' => $twoMonths))
            ->getQuery();

        $meetings = $query->getResult();

        $output['meetings'] = $meetings;
        
        return $output;
    }

    /**
     * @Route("/locale/{locale}", name="change_locale")
     * @Template()
     */
    public function changeLocaleAction($locale)
    {
        $request = $this->getRequest();

        // some logic to determine the $locale
        $request->getSession()->set('_locale', $locale);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}
