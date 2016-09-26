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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

use Proethos2\ModelBundle\Entity\User;


class AjaxController extends Controller
{
    /**
     * @Route("/api/users/get/", name="api_get_user_by_id")
     * @Template()
     */
    public function getUserByIdAction()
    {

        $data = array();

        $request = $this->getRequest();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        // checking if was a post request
        if($request->isMethod('POST')) {

            // getting post data
            $post_data = $request->request->all();

            $em = $this->getDoctrine()->getManager();
            $user_repository = $em->getRepository('Proethos2ModelBundle:User');
            $data = $user_repository->find($post_data['id']);
        }

        $jsonContent = $serializer->serialize($data, 'json');
        var_dump($jsonContent);
        $response = new JsonResponse();
        $response->setContent($jsonContent);

        return $response;
    }

}
