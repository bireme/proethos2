<?php

// This file is part of the ProEthos Software. 
// 
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software. 
// 
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://github.com/bireme/proethos2/blob/master/LICENSE.txt


namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Proethos2\CoreBundle\Util\Util;


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
        foreach($protocol_repository->findBy(array("owner" => $user), array('id' => 'DESC')) as $submission) {
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

    public function getLogoAction()
    {   
        $util = new Util($this->container, $this->getDoctrine());
        $committee_logourl = $util->getConfiguration("committee.logourl");

        if(!empty($committee_logourl))
            return new Response($committee_logourl);
        
        return new Response();

    }
}
