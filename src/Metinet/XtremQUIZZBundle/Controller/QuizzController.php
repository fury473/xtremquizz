<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\XtremQUIZZBundle\Form\ProcessQuestionType;

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
        
        $friendUsers = $fbUserManager->getFriendUsersWhoCompletedQuizz($fbUserManager->getMyFbId(), $id);
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
        $fbUserManager = $this->container->get('metinet.manager.fbuser');

        // On récupère le quizz
        $quizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->find($id);
        if (!$quizz) { throw $this->createNotFoundException('Quizz introuvable.'); }
        
        // On récupère l'utilisateur
        $user = $em->getRepository('MetinetXtremQUIZZBundle:User')->find($fbUserManager->getMyId());
        if (!$user) { throw $this->createNotFoundException('Utilisateur introuvable.'); }
        
        // On récupère les questions du quizz
        $questions = $quizz->getQuestions();
        $questionsForm = array();
        
        // On créer un formulaire par question que l'on stocke dans un tableau
        foreach($questions as $question) {
            // On regarde si l'utilisateur a déjà répondu à la question.
            // Pour cela on récupère d'abord toutes les réponses de cette question
            $answers = $em->getRepository('MetinetXtremQUIZZBundle:Answer')->getAnswersToQuestion($question);
            $answered = false;
            foreach($answers as $answer) {
                // Puis pour chaque réponse, on regarde si l'utilisateur l'a déjà choisie
                if(in_array($user, $answer->getUsers()->toArray())) {
                    // Si la réponse est associée à l'utilisateur l'utilisateur a donc répondu à la question
                    $answered = true;
                }
            }
            
            if(!$answered) {
                // On génère le FormType de la question seulement si celle-ci n'a pas déjà eu de réponse
                $questionForm  = $this->createForm(new ProcessQuestionType(), array('question' => $question));
                array_push($questionsForm, $questionForm->createView());
            }
        }
        
        // On mélange les questions
        shuffle($questionsForm);

        return array(
            'quizz' => $quizz,
            'questionsForm'   => $questionsForm
        );
    }
    
    /**
     * Start of Quizz
     *
     * @Route("/{id}/start", name="quizz_start")
     * @Template()
     */
    public function startAction($id)
    {
        return array();
    }
    
    /**
     * Validation of Quizz
     *
     * @Route("/{id}/end", name="quizz_end")
     * @Template()
     */
    public function endAction($id)
    {
        return array();
    }
}
