<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Quizz controller.
 *
 * @Route("/quizz")
 */
class QuizzController extends Controller
{
    /**
     * @Route("/", name="quizz")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $allQuizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->findAll();

        return array(
            'entities' => $allQuizz,
        );
    }
    
    /**
     * Finds and displays a Quizz entity.
     *
     * @Route("/{id}/show", name="quizz_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $fbUserManager = $this->container->get('metinet.manager.fbuser');
        
        $friendUsers = $fbUserManager->getUserFriendUsers("me");
        $quizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);
        $top10 = $em->getRepository('MetinetXtremQUIZZBundle:User')->getRank(10);
        $quizzResult = $em->getRepository('MetinetXtremQUIZZBundle:QuizzResult')->findByUserIdAndQuizzId($fbUserManager->getMyId(), $id);
        
        if (is_null($friendUsers)) {
            $array = array(
                'quizz' => $quizz,
                'quizzResult' => $quizzResult,
                'top10' => $top10
            );
        } else {
            $array = array(
                'quizz' => $quizz,
                'quizzResult' => $quizzResult,
                'top10' => $top10,
                'friendUsers' => $friendUsers
            );
        }
        

        if (!$quizz) {
            throw $this->createNotFoundException('Quizz Introuvable.');
        }

        return $array;
    }
    
    /**
     * Process of Quizz
     *
     * @Route("/{id}/process", name="quizz_process")
     * @Template()
     */
    public function processAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $quizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);

        if (!$quizz) {
            throw $this->createNotFoundException('Quizz Introuvable.');
        }

        return array(
            'quizz' => $quizz
        );
    }
    
    /**
     * Validation of Quizz
     *
     * @Route("/{id}/validate", name="quizz_validate")
     * @Template()
     */
    public function validateAction($id)
    {
        return array();
    }
}
