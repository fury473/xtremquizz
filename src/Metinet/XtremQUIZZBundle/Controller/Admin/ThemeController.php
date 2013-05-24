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

        return array(
            'entity'      => $entity,
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

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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

        $editForm = $this->createForm(new ThemeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_theme_show', array('id' => $entity->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * Deletes a Theme entity.
     *
     * @Route("/{id}/delete", name="admin_theme_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_theme'));
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
