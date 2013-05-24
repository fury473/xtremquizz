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
        $tab = Array();
        foreach($allQuizz as $key=>$q){
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
            'entities' => $allQuizz,
            'descriptions' => $tab
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
        $quizzResult = $em->getRepository('MetinetXtremQUIZZBundle:QuizzResult')->getByUserIdAndQuizzId($fbUserManager->getMyId(), $id);
        
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
        
        if(count($questionsForm) > 0) {
            // On mélange les questions
            shuffle($questionsForm);

            return array(
                'quizz' => $quizz,
                'userId' => $user->getId(),
                'questionsForm'  => $questionsForm
            );
        } else { //S'il n'y a pas de question c'est que le quizz est terminé on redirige alors sur la page de résultat
            return $this->redirect($this->generateUrl('quizz_result', array('id' => $id)), 301);
        }
    }
     
    /**
     * @Route("/{id}/", name="quizz_list")
     * @Template()
     */
    public function listAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        if($id == 0){
           $allQuizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->findAll();

           return array(
               'entities' => $allQuizz,
               'theme' => 0,
           );   
        }
        else{
           $allQuizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->getQuizzByTheme($id)->execute();
           $theme =  $em->getRepository('MetinetXtremQUIZZBundle:Theme')->getThemeByID($id)->execute();
           var_dump($allQuizz);
           return array(
               'entities' => $allQuizz,
               'theme' => $theme[0],
           ); 
        }
    }
}
