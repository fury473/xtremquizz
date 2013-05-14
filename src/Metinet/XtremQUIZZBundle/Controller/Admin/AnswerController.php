<?php

namespace Metinet\XtremQUIZZBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * Lists all Answer entities.
     *
     * @Route("/", name="admin_answer")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Answer entity.
     *
     * @Route("/create", name="admin_answer_create")
     * @Method("POST")
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

            return $this->redirect($this->generateUrl('admin_answer_show', array('id' => $entity->getId())));
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
     * Finds and displays a Answer entity.
     *
     * @Route("/{id}", name="admin_answer_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
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
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Answer entity.
     *
     * @Route("/{id}", name="admin_answer_update")
     * @Method("PUT")
     * @Template("MetinetXtremQUIZZBundle:Answer:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AnswerType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_answer_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Answer entity.
     *
     * @Route("/{id}", name="admin_answer_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Answer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_answer'));
    }

    /**
     * Creates a form to delete a Answer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
