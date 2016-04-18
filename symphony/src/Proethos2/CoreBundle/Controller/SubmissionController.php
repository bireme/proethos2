<?php

namespace Proethos2\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Proethos2\CoreBundle\Entity\Submission;

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

        $submissions = $em->getRepository('Proethos2ModelBundle:Submission')->findAllInOrder();

        return array(
            'submissions' => $submissions,
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

        $submission = $em->getRepository('Proethos2ModelBundle:Submission')->find($id);

        if (!$submission) {
            throw $this->createNotFoundException('Unable to find submission.');
        }

        return array(
            'submission' => $submission,
        );
    }
}
