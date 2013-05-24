<?php

namespace Metinet\XtremQUIZZBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\XtremQUIZZBundle\Entity\Answer;
use Metinet\XtremQUIZZBundle\Form\AnswerType;

/**
 * Answer controller.
 *
 * @Route("/admin_answer")
 */
class AnswerController extends Controller
{
    /**
     * Lists question Answers.
     *
     * @Route("/{id}/answers", name="admin_answer")
     * @Template()
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->findByQuestion($id);

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Answer entity.
     *
     * @Route("/create", name="admin_answer_create")
     * @Template("MetinetXtremQUIZZBundle:Answer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Answer();
        $form = $this->createForm(new AnswerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_question_show', array('id' => $entity->getQuestion()->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Answer entity.
     *
     * @Route("/new", name="admin_answer_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Answer();
        $form   = $this->createForm(new AnswerType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Displays a form to create a new Answer entity to a question.
     *
     * @Route("/{id}/new", name="admin_answer_new_question")
     * @Template("MetinetXtremQUIZZBundle:Admin/Answer:new.html.twig")
     */
    public function newForQuestionAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('MetinetXtremQUIZZBundle:Question')->find($id);
        $entity = new Answer();
        $entity->setQuestion($question);
        $form   = $this->createForm(new AnswerType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Answer entity.
     *
     * @Route("/{id}/show", name="admin_answer_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }


        return array(
            'entity'      => $entity
        );
    }

    /**
     * Displays a form to edit an existing Answer entity.
     *
     * @Route("/{id}/edit", name="admin_answer_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $editForm = $this->createForm(new AnswerType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Edits an existing Answer entity.
     *
     * @Route("/{id}/update", name="admin_answer_update")
     * @Template("MetinetXtremQUIZZBundle:Answer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $editForm = $this->createForm(new AnswerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_question_show', array('id' => $entity->getQuestion()->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        );
    }

    /**
     * Deletes a Answer entity.
     *
     * @Route("/{id}/delete", name="admin_answer_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $questionId = $entity->getQuestion()->getId();
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_question_show', array('id' => $questionId)));
    }
}
