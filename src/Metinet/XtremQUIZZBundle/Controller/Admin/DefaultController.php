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
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        
        return array();
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
    
    /**
     * @Template()
     */
    public function nbJoueurAction()
    {
        $rep = $this->getDoctrine()->getRepository('MetinetXtremQUIZZBundle:User');
        $nbJoueur = $rep->getNbJoueur()->execute();

        return array('nbJoueur' => $nbJoueur);
    }
}
