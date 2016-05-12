<?php

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
