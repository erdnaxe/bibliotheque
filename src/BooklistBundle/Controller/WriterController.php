<?php

namespace BooklistBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BooklistBundle\Entity\Writer;
use BooklistBundle\Form\WriterType;

/**
 * Writer controller.
 *
 * @Route("/writer")
 */
class WriterController extends Controller
{

    /**
     * Lists all Writer entities.
     *
     * @Route("/", name="writer")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BooklistBundle:Writer')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Writer entity.
     *
     * @Route("/", name="writer_create")
     * @Method("POST")
     * @Template("BooklistBundle:Writer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Writer();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('writer_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Writer entity.
     *
     * @param Writer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Writer $entity)
    {
        $form = $this->createForm(new WriterType(), $entity, array(
            'action' => $this->generateUrl('writer_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Writer entity.
     *
     * @Route("/new", name="writer_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Writer();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Writer entity.
     *
     * @Route("/{id}", name="writer_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BooklistBundle:Writer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Writer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Writer entity.
     *
     * @Route("/{id}/edit", name="writer_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BooklistBundle:Writer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Writer entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Writer entity.
    *
    * @param Writer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Writer $entity)
    {
        $form = $this->createForm(new WriterType(), $entity, array(
            'action' => $this->generateUrl('writer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Writer entity.
     *
     * @Route("/{id}", name="writer_update")
     * @Method("PUT")
     * @Template("BooklistBundle:Writer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BooklistBundle:Writer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Writer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('writer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Writer entity.
     *
     * @Route("/{id}", name="writer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BooklistBundle:Writer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Writer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('writer'));
    }

    /**
     * Creates a form to delete a Writer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('writer_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
