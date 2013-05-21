<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

    /**
     * Answer controller.
     * 
     * @Route("/answer")
     */
class AnswerController extends Controller {

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction() {

        return array();
    }

    /**
     * Valide une réponse à une question par un utilisateur
     * 
     * @Route("/validate", name="answer_validate")
     */
    public function validateAction() {
        $content = $this->get('request')->request->all();
        $answerId = $content['answer_id'];
        if ($this->get('request')->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $fbUserManager = $this->container->get('metinet.manager.fbuser');
            
            $answer  = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->find($answerId);
            $user = $em->getRepository('MetinetXtremQUIZZBundle:User')->find($fbUserManager->getMyId());
            $answer->addUser($user);
            try {
                $em->persist($answer);
                $em->flush();
            } catch(Exception $e) {
                return new Response($e->getMessage(), 500);
            }
            return new Response(0, 200);
            
        } else {
            $return = json_encode(-1);
            return new Response($return, 500, array('Content-Type' => 'application/json'));
        }
    }

}
