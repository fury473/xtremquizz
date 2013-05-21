<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\XtremQUIZZBundle\Entity\QuizzResult;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/quizz-result")
 */
class QuizzResultController extends Controller
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
     * Génère le QuizzResult entre un user et un quizz et y stoque la date de création
     * 
     * @Route("/start", name="quizz_start")
     */
    public function startAction() {
        $content = $this->get('request')->request->all();
        $userId = $content['user_id'];
        $quizzId = $content['quizz_id'];
        if ($this->get('request')->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $quizzResult = $em->getRepository('MetinetXtremQUIZZBundle:QuizzResult')->getByUserIdAndQuizzId($userId, $quizzId);
            if(is_null($quizzResult)) {
                $quizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($quizzId);
                $user = $em->getRepository('MetinetXtremQUIZZBundle:User')->find($userId);
                $quizzResult = new QuizzResult();
                $quizzResult->setUser($user);
                $quizzResult->setQuizz($quizz);
                $quizzResult->setDateStart(new \DateTime());
                try {
                    $em->persist($quizzResult);
                    $em->flush();
                } catch(Exception $e) {
                    return new Response($e->getMessage(), 500);
                }
            }
            
            $response = new Response();
            $response->setContent(json_encode(array(
                'createdAt' => $quizzResult->getDateStart()->getTimestamp(),
                'quizz_result_id' => $quizzResult->getId()
            )));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        } else {
            return new Response("La requête doit être de type XmlHttpRequest.", 500);
        }
    }

/**
     * Complète le QuizzResult
     * 
     * @Route("/end", name="quizz_end")
     */
    public function endAction() {
        $content = $this->get('request')->request->all();
        $quizzResultId = $content['quizz_result_id'];
        if ($this->get('request')->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            $quizzResult = $em->getRepository('MetinetXtremQUIZZBundle:QuizzResult')->find($quizzResultId);
            if(!is_null($quizzResult)) {
                $quizzResult->setDateEnd(new \DateTime());
                try {
                    $em->persist($quizzResult);
                    $em->flush();
                } catch(Exception $e) {
                    return new Response($e->getMessage(), 500);
                }
            } else {
                throw $this->createNotFoundException('QuizzResult introuvable.');
            }
            
            $response = new Response();
            $response->setContent(json_encode(array(
                'elapsedTime' => ($quizzResult->getDateEnd()->getTimestamp() - $quizzResult->getDateStart()->getTimestamp())
            )));
            $response->headers->set('Content-Type', 'application/json');
            
            return $response;
        } else {
            return new Response("La requête doit être de type XmlHttpRequest.", 500);
        }
    }
}