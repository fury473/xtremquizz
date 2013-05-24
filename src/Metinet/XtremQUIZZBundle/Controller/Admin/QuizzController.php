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
        
        $tab = Array();
        foreach($entities as $key=>$q){
            $chaine = $q->getShortDesc();
            $key = $q->getId();
            if (strlen($chaine) > 40) {
		$chaine = substr($chaine, 0, 40);
		$position_espace = strrpos($chaine, " ");
		$texte = substr($chaine, 0, $position_espace); 
		$chaine = $texte."...";
            }
            $tab[$key] = $chaine;
        }

        return array(
            'entities' => $entities,
            'descriptions' => $tab
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
        $repQuestion = $this->getDoctrine()->getRepository('MetinetXtremQUIZZBundle:Question');
        $nbQuestion = $repQuestion->getnbQuestionParQuizz($id)->execute();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'nbQuestion' => $nbQuestion[0][1]
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

            return $this->redirect($this->generateUrl('admin_quizz_show', array('id' => $id)));
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
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $em->remove($entity);
        $em->flush();

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
