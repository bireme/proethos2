<?php

namespace Proethos2\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProtocolController extends Controller
{
    /**
     * @Route("/protocol/{protocol_id}", name="protocol_show_protocol")
     * @Template()
     */
    public function showProtocolAction($protocol_id)
    {
        return array();    
    }

}
