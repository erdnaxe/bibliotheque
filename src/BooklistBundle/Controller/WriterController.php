<?php

namespace BooklistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Writer controller.
 *
 * @Route("/")
 */
class WriterController extends Controller {

    /**
     * Lists all Book entities of this writer.
     *
     * @Route("/writer/{name}", name="writer_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($name) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('BooklistBundle:Book')->findByWriter($name);

        return array(
            'entities' => $entities,
            'name' => $name,
        );
    }

}
