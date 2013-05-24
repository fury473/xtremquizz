<?php

namespace Metinet\XtremQUIZZBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\XtremQUIZZBundle\Entity\Question;
use Metinet\XtremQUIZZBundle\Form\QuestionType;

/**
 * Question controller.
 *
 * @Route("/admin_question")
 */
class QuestionController extends Controller
{
    /**
     * Lists quizz questions.
     *
     * @Route("/{id}/questions", name="admin_question")
     * @Template("")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MetinetXtremQUIZZBundle:Question')->findByQuizz($id);

        return array(
            'entities' => $entities,
            'quizz_id' => $id
        );
    }

    /**
     * Creates a new Question entity.
     *
     * @Route("/create", name="admin_question_create")
     * @Template("MetinetXtremQUIZZBundle:Question:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Question();
        $form = $this->createForm(new QuestionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_question_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Question entity.
     *
     * @Route("/new", name="admin_question_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Question();
        $form   = $this->createForm(new QuestionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Question entity to a quizz.
     *
     * @Route("/{id}/new", name="admin_question_new_quizz")
     * @Template("MetinetXtremQUIZZBundle:Admin/Question:new.html.twig")
     */
    public function newForQuizzAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $quizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);
        $entity = new Question();
        $entity->setQuizz($quizz);
        $form   = $this->createForm(new QuestionType(), $entity);

        return array(
            'entity' => $entity,
            'quizz_id' => $id,
            'form'   => $form->createView()
        );
    }

    /**
     * Finds and displays a Question entity.
     *
     * @Route("/{id}/show", name="admin_question_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        return array(
            'entity'      => $entity
        );
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     * @Route("/{id}/edit", name="admin_question_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createForm(new QuestionType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing Question entity.
     *
     * @Route("/{id}/update", name="admin_question_update")
     * @Template("MetinetXtremQUIZZBundle:Question:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createForm(new QuestionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_question_show', array('id' => $entity->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Question entity.
     * @Route("/{id}/delete", name="admin_question_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $quizzId = $entity->getQuizz()->getId();
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_question', array('id' => $quizzId)));
    }
    
    /**
    * @Template()
    */
    public function uploadAction()
    {
        $document = new Document();
        $form = $this->createFormBuilder($document)
            ->add('name')
            ->add('file')
            ->getForm()
        ;

        if ($this->getRequest()->getMethod() === 'POST') {
            $form->bindRequest($this->getRequest());
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($document);
                $em->flush();
            }
        }

        return array('form' => $form->createView());
    }
    
    public function upload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->getFile()) {
            return;
        }

        // la méthode « move » prend comme arguments le répertoire cible et
        // le nom de fichier cible où le fichier doit être déplacé
        $this->getFile()->move($this->getUploadRootDir(), $this->getFile()->getClientOriginalName());

        // définit la propriété « path » comme étant le nom de fichier où vous
        // avez stocké le fichier
        $this->path = $this->getFile()->getClientOriginalName();

        // « nettoie » la propriété « file » comme vous n'en aurez plus besoin
        $this->file = null;
    }
}
