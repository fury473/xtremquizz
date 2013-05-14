<?php

namespace Metinet\XtremQUIZZBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default controller.
 *
 * @Route("/admin")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin")
     * @Template()
     */
    public function indexAction()
    {
        $rep = $this->getDoctrine()->getRepository('MetinetXtremQUIZZBundle:User');
        $nbJoueur = $rep->getNbJoueur()->execute();
        $nbJoueur7j = $rep->getNbJoueur7jours()->execute();
        $nbJoueur30j = $rep->getNbJoueur30jours()->execute();

        return array('nbJoueur' => $nbJoueur[0][1], 'nbJoueur7j' => $nbJoueur7j[0][1], 'nbJoueur30j' => $nbJoueur30j[0][1]);
    }

        
    /**
     * Action de login
     * 
     * @Route("/connexion", name="connexion")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        
        // On vérifie l’identification
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return array('error' => $error);
    }
    
     /**
     * Action de check login
     *
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction() 
    {
        
    }
    
     /**
     * Action de logout
     * @Route("/deconnexion", name="deconnexion")
     *
     */
    public function logoutAction()
    {
    
    }
}
