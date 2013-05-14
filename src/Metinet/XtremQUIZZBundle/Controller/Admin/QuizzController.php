<?php

namespace Metinet\XtremQUIZZBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\XtremQUIZZBundle\Entity\Quizz;
use Metinet\XtremQUIZZBundle\Form\QuizzType;

/**
 * Quizz controller.
 *
 * @Route("/admin_quizz")
 */
class QuizzController extends Controller
{
    /**
     * Lists all Quizz entities.
     *
     * @Route("/", name="admin_quizz")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Quizz entity.
     *
     * @Route("/create", name="admin_quizz_create")
     * @Template("MetinetXtremQUIZZBundle:Quizz:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Quizz();
        $form = $this->createForm(new QuizzType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_quizz_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Quizz entity.
     *
     * @Route("/new", name="admin_quizz_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Quizz();
        $form   = $this->createForm(new QuizzType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Quizz entity.
     *
     * @Route("/{id}/show", name="admin_quizz_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Quizz entity.
     *
     * @Route("/{id}/edit", name="admin_quizz_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $editForm = $this->createForm(new QuizzType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Quizz entity.
     *
     * @Route("/{id}/update", name="admin_quizz_update")
     * @Template("MetinetXtremQUIZZBundle:Quizz:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new QuizzType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_quizz_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Quizz entity.
     * @Route("/{id}/delete", name="admin_quizz_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Quizz entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_quizz'));
    }

    /**
     * Creates a form to delete a Quizz entity by id.
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
