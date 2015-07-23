<?php

namespace BooklistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Search controller.
 *
 * @Route("/")
 */
class SearchController extends Controller {

    /**
     * Lists all Book entities of the search.
     *
     * @Route("/search/{name}", name="search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction($name) {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('BooklistBundle:Book')->findByWriter($name);

        return array('entities' => $entities);
    }

    /**
     * Creates a form to edit a Book entity.
     *
     * @param Book $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm() {
        $form = $this->createForm(null, null, array(
            'action' => $this->generateUrl('search'),
            'method' => 'GET',
        ));

        $builder->add(
                'value', 'text', array(
            'attr' => array(
                'input_group' => array(
                    'prepend' => '.icon-cloud',
                    'append' => '.icon-off',
                    'size' => 'small'
                )
            )
                )
        );

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

}
