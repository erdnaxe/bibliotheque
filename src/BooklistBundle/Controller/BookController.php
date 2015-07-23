<?php

namespace BooklistBundle\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BooklistBundle\Entity\Book;
use BooklistBundle\Form\BookType;

/**
 * Book controller.
 *
 * @Route("/")
 */
class BookController extends Controller {

    /**
     * Lists all Book entities.
     *
     * @Route("/", name="book_list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('BooklistBundle:Book')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a form to create a Book entity.
     *
     * @param Book $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Book $entity) {
        $form = $this->createForm(new BookType(), $entity, array(
            'action' => $this->generateUrl('book_new'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'form.new_book.submit'));

        return $form;
    }

    /**
     * Creates a form for the ISBN.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createISBNForm() {
        $form = $this->createFormBuilder();
        $form->add('isbn', 'text', array('label' => 'form.isbn.input'));
        $form->add('submit', 'submit', array('label' => 'form.isbn.submit'));

        return $form->getForm();
    }

    /**
     * Displays a form to create a new Book entity.
     *
     * @Route("/book/new", name="book_new")
     * @Template()
     */
    public function newAction(Request $request) {
        $flash = $this->get('braincrafted_bootstrap.flash');
        $entity = new Book();
        $form_isbn = $this->createISBNForm();

        // Si le champ d'ISBN est valide, on complète le formulaire
        $form_isbn->handleRequest($request);
        if ($form_isbn->isValid()) {
            $book = $this->downloadInfos($form_isbn->get('isbn')->getData());
            $flash->info('Le livre a été trouvé (ISBN : ' . $form_isbn->get('isbn')->getData() . ').');

            $entity->setTitle($book["Title"]);
            $entity->setWriter($book["AuthorsText"]);
            $entity->setEditor($book["PublisherText"]['#']);
        }

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        // Si le livre est valide, on l'ajoute à la BDD
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $flash->success('Le livre "' . $entity->getTitle() . '" a été ajouté !');

            return $this->redirect($this->generateUrl('book_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'form_isbn' => $form_isbn->createView(),
        );
    }

    /**
     * Finds and displays a Book entity.
     *
     * @Route("/book/{id}", name="book_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BooklistBundle:Book')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Book entity.');
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Book entity.
     *
     * @Route("/book/{id}/edit", name="book_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BooklistBundle:Book')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Book entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Book entity.
     *
     * @param Book $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Book $entity) {
        $form = $this->createForm(new BookType(), $entity, array(
            'action' => $this->generateUrl('book_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Modifier'));

        return $form;
    }

    /**
     * Edits an existing Book entity.
     *
     * @Route("/book/{id}", name="book_update")
     * @Method("PUT")
     * @Template("BooklistBundle:Book:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BooklistBundle:Book')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Book entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('book_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Book entity.
     *
     * @Route("/book/{id}/delete", name="book_delete")
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('BooklistBundle:Book')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Book entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('book_list'));
    }

    /**
     * Fonction pour télécharger les informations d'un livre
     * 
     * @param string $isbn ISBN
     * @return array Livre
     * @throws \Exception
     */
    private function downloadInfos($isbn) {
        $serializer = new Serializer(array(), array(new XmlEncoder()));

        // Récupération du livre
        $url = "http://isbndb.com/api/books.xml?access_key=45L5NTHL&index1=isbn&value1=" . $isbn;
        if (!$reponse = file_get_contents($url)) {
            throw new \Exception('Impossible de télécharger les résultats !');
        }

        if (!$book = $serializer->decode($reponse, 'xml')['BookList']['BookData']) {
            throw new \Exception('Le livre n\'a pas été trouvé : ' . $isbn);
        }

        return $book;
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
            'action' => $this->generateUrl('list'),
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
