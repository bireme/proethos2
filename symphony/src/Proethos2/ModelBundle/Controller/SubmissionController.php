<?php

namespace Proethos2\ModelBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proethos2\ModelBundle\Entity\Submission;

/**
 * Submission controller.
 *
 * @Route("/crud/submission")
 */
class SubmissionController extends Controller
{

    /**
     * Lists all Submission entities.
     *
     * @Route("/", name="crud_submission")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Proethos2ModelBundle:Submission')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Submission entity.
     *
     * @Route("/{id}", name="crud_submission_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Proethos2ModelBundle:Submission')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Submission entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
