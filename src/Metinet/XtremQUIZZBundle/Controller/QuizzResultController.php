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
     * Resultat d'un Quizz
     *
     * @Route("/{id}", name="quizz_result")
     * @Template()
     */
    public function resultAction($id)
    {
        echo 'test';
        $em = $this->getDoctrine()->getManager();
        $fbUserManager = $this->container->get('metinet.manager.fbuser');
        
        // On récupère le quizz
        $quizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);
        if (!$quizz) { throw $this->createNotFoundException('Quizz introuvable.'); }
        
        $userId = $fbUserManager->getMyId();
        
        $quizzResult = $em->getRepository('MetinetXtremQUIZZBundle:QuizzResult')->getByUserIdAndQuizzId($userId, $id);
        $txtWin = null;
        if ($quizzResult->getAverage() == 100) {
            $txtWin = $quizz->getTxtWin1();
        } else if ($quizzResult->getAverage() > 75) {
            $txtWin = $quizz->getTxtWin2();
        } else if ($quizzResult->getAverage() > 50) {
            $txtWin = $quizz->getTxtWin3();
        } else if ($quizzResult->getAverage() > 25) {
            $txtWin = $quizz->getTxtWin4();
        }

        $quizz = array(
            'id' => $id,
            'title' => $quizz->getTitle(),
            'txtWin' => $txtWin,
            'averageTime' => $quizz->getAverageTime(),
            'picture' => $quizz->getPicture()
        );
        
        // On fait une phrase pour décrire le temps écoulé
        $elapsedTime = $quizzResult->getDateEnd()->diff($quizzResult->getDateStart());
        if ($elapsedTime->format('%h') > 0) {
            $plurielH = '';
            $plurielM = '';
            $plurielS = '';
            if ($elapsedTime->format('%h') > 1) { $plurielH = 's'; }
            if ($elapsedTime->format('%i') > 1) { $plurielM = 's'; }
            if ($elapsedTime->format('%s') > 1) { $plurielS = 's'; }
            $elapsedTime = $elapsedTime->format("%h heure$plurielH, %i minute$plurielM et %s seconde$plurielS");
        } else if ($elapsedTime->format('%m') > 0) {
            $plurielM = '';
            $plurielS = '';
            if ($elapsedTime->format('%i') > 1) { $plurielM = 's'; }
            if ($elapsedTime->format('%s') > 1) { $plurielS = 's'; }
            $elapsedTime = $elapsedTime->format("%i minute$plurielM et %s seconde$plurielS");
        } else {
            $plurielS = '';
            if ($elapsedTime->format('%s') > 1) { $plurielS = 's'; }
            $elapsedTime = $elapsedTime->format("%s seconde$plurielS");
        }

        $quizzResult = array(
            'elapsedTime' => $elapsedTime,
            'average' => $quizzResult->getAverage()
        );

        if ($this->get('request')->isXmlHttpRequest()) {
            $response = new Response();
            $response->setContent(json_encode(array(
                'quizz' => $quizz,
                'userId' => $userId,
                'quizzResult' => $quizzResult
            )));
            $response->headers->set('Content-Type', 'application/json');
            return response;
        }
        return array(
            'quizz' => $quizz,
            'userId' => $userId,
            'quizzResult' => $quizzResult
        );
    }
    
    /**
     * Génère le QuizzResult entre un user et un quizz et y stoque la date de création
     * 
     * @Route("/start", name="quizz_result_start")
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
     * @Route("/end", name="quizz_result_end")
     */
    public function endAction() {
        $content = $this->get('request')->request->all();
        $quizzResultId = $content['quizz_result_id'];
        if ($this->get('request')->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            // On récupère le QuizzResult
            $quizzResult = $em->getRepository('MetinetXtremQUIZZBundle:QuizzResult')->find($quizzResultId);
            if(!is_null($quizzResult)) {
                // On enregistre la date à laquelle l'utilisateur a achevé le quizz
                $quizzResult->setDateEnd(new \DateTime());
                
                /* CALCUL DU SCORE : RECUPERATION DES BONNES REPONSES */
                // On récupère les questions du quizz
                $questions = $quizzResult->getQuizz()->getQuestions();
                
                $fbUserManager = $this->container->get('metinet.manager.fbuser');
                $user = $em->getRepository('MetinetXtremQUIZZBundle:User')->find($fbUserManager->getMyId());
                // On stoque le nombre de questions du quizz
                $nbQuestions = count($questions);
                // One le comparera par la suite au nombre de bonnes réponses de l'utilisateur
                $nbGoodAnswers = 0;
                // On parcours les questions
                foreach($questions as $question) {
                    // On regarde si l'utilisateur a répondu à la question.
                    // Pour cela on récupère d'abord toutes les réponses de cette question
                    $answers = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->getAnswersToQuestion($question);
                    foreach($answers as $answer) {
                        // Puis pour chaque réponse, on regarde si celle-ci est correct
                        if($answer->getIsCorrect()) {
                            if(in_array($user, $answer->getUsers()->toArray())) {
                                // Si la réponse est associée à l'utilisateur, on incrémente son nombre de bonnes réponses
                                $nbGoodAnswers++;
                            }
                        }
                    }
                }
                
                /* CALCUL DU SCORE : Taux de réussite + Points gagnés */
                $average = $nbGoodAnswers/$nbQuestions;
                $winPoints  = 100;
                
                $quizzResult->setAverage($average);
                $quizzResult->setWinPoints($winPoints);
                try {
                    $em->persist($quizzResult);
                    $em->flush();
                } catch(Exception $e) {
                    return new Response($e->getMessage(), 500);
                }
            } else {
                throw $this->createNotFoundException('QuizzResult introuvable.');
            }
            return new Response(null, 200);
        } else {
            return new Response("La requête doit être de type XmlHttpRequest.", 500);
        }
    }
}