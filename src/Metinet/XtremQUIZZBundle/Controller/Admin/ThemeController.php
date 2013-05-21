<?php

namespace Metinet\XtremQUIZZBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\XtremQUIZZBundle\Entity\Theme;
use Metinet\XtremQUIZZBundle\Form\ThemeType;

/**
 * Theme controller.
 *
 * @Route("/admin_theme")
 */
class ThemeController extends Controller
{
    /**
     * Lists all Theme entities.
     *
     * @Route("/", name="admin_theme")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetXtremQUIZZBundle:Theme')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Theme entity.
     *
     * @Route("/create", name="admin_theme_create")
     * @Template("MetinetXtremQUIZZBundle:Theme:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Theme();
        $form = $this->createForm(new ThemeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_theme_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Theme entity.
     *
     * @Route("/new", name="admin_theme_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Theme();
        $form   = $this->createForm(new ThemeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Theme entity.
     *
     * @Route("/{id}/show", name="admin_theme_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Theme')->find($id);
        $rep = $this->getDoctrine()->getRepository('MetinetXtremQUIZZBundle:Quizz');
        $quizzParTheme = $rep->getQuizzByTheme($id)->execute();
            
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'quizz' => $quizzParTheme
        );
    }

    /**
     * Displays a form to edit an existing Theme entity.
     *
     * @Route("/{id}/edit", name="admin_theme_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $editForm = $this->createForm(new ThemeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Theme entity.
     *
     * @Route("/{id}/update", name="admin_theme_update")
     * @Template("MetinetXtremQUIZZBundle:Theme:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ThemeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_theme_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Theme entity.
     *
     * @Route("/{id}/delete", name="admin_theme_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetinetXtremQUIZZBundle:Theme')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Theme entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_theme'));
    }

    /**
     * Creates a form to delete a Theme entity by id.
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
