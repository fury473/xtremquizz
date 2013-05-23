<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getDoctrine()->getRepository('MetinetXtremQUIZZBundle:User');
        $quizz = $this->getDoctrine()->getRepository('MetinetXtremQUIZZBundle:Quizz');
        $points = $user->getPoints()->execute();
        $totalJoueur = $user->getNbJoueur()->execute();
        $QuizzIds = $quizz->getLastQuizzId()->execute();
        $promotedQuizz = $quizz->getPromotedQuizz()->execute();
        $fbUserManager = $this->container->get('metinet.manager.fbuser');
        $myfbUid = $fbUserManager->getMyFbId();
        $averageTime = $user->getAverageTime($myfbUid)->execute();
        $listfriends = $fbUserManager->getFbFriends($myfbUid);
        if($averageTime[0]['averageTime'] != NULL)
        {
            $classementJoueur = $user->getClassementJoueur($points[0]['points'],$averageTime[0]['averageTime'])->execute();
            $myAverage = $averageTime[0]['averageTime'];
            $classementJoueur[0][1]++;
        }
        else 
        {
            $myAverage = NULL;
            $classementJoueur[0][1] = NULL;
        }

        $i = 0;
        $friends = $this->container->get('metinet.manager.fbuser')->getFbFriends("me");
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MetinetXtremQUIZZBundle:User')->getClassementAll()->execute();
        $temp_points = 0;
        $temp_time = 0;

        foreach ($entities as $key => $row) {
            $averageTime[$key] = $row['averageTime'];
            $points[$key] = $row['points'];
        }
        // tri en priorité en fonction des points puis en fonction du temps moyen
        array_multisort($points, SORT_DESC, $averageTime, SORT_ASC, $entities);
        $j =0;
        foreach($entities as $user){
            $entities[$i]['rank'] = $i+1;
            $temp_points = $user['points'];
            $temp_time = $user['averageTime'];
            if($myfbUid == $user['fbUid']){
               $myRank =   $entities[$i]['rank']; 
            }
             //Recherche du nombre d'amis jouant au jeu :
            foreach($listfriends['data'] as $friend){
                if($user['fbUid'] == $friend['id']){
                        $j++;
                }
           }                
            $i++;
        }
        //Booléen permettant l'affichage de la liste d'amis ou générale :
        if($j >= 4)
            $displayFriend = 1;
        else
            $displayFriend = 0;
        
        return array("friends" => $friends['data'],
                    "points" => $points[0],
                    "LastQuizz" => $QuizzIds,
                    "ListeFriends" => $listfriends,
                    "totalJoueur" => $totalJoueur[0][1],
                    "averageTime" => $myAverage,
                    "classementJoueur" => $classementJoueur[0][1],
                    "entities" => $entities,
                    "displayFriend" => $displayFriend,
                    'myRank' => $myRank,
                    "promotedQuizz" => $promotedQuizz[0],
                    );
    }
}
