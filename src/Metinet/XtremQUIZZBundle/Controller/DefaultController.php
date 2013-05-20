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
        $points = $user->getPoints()->execute();
        $totalJoueur = $user->getNbJoueur()->execute();
        $averageTime = $user->getAverageTime()->execute();
        if($averageTime != NULL)
        {
            $classementJoueur = $user->getClassementJoueur($points[0]['points'],$averageTime[0]['averageTime'])->execute();
            
            $classementJoueur[0][1]++;
        }
        else 
        {
            $classementJoueur[0][1] = NULL;
        }
        $i = 0;
        $friends = $this->container->get('metinet.manager.fbuser')->getUserFriends("me");
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('MetinetXtremQUIZZBundle:User')->getClassementAll()->execute();
        $temp_points = 0;
        $temp_time = 0;

        $score = array();
        foreach($entities as $user){
            $score[$i] = $user['points'];
        }
        foreach ($entities as $key => $row) {
            $averageTime[$key] = $row['averageTime'];
            $points[$key] = $row['points'];
        }
        // tri en priorité en fonction des points puis en fonction du temps moyen
        array_multisort($points, SORT_DESC, $averageTime, SORT_ASC, $entities);
        foreach($entities as $user){
            $entities[$i]['rank'] = $i+1;
            $temp_points = $user['points'];
            $temp_time = $user['averageTime'];
            $i++;
        }
        return array("friends" => $friends['data'],
                    "points" => $points[0]['points'],
                    "totalJoueur" => $totalJoueur[0][1],
                    "averageTime" => $averageTime[0]['averageTime'],
                    "classementJoueur" => $classementJoueur[0][1],
                    "entities" => $entities,
                    );
    }
}
