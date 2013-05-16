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
            var_dump($classementJoueur);
        }
        else 
        {
            $classementJoueur[0][1] = NULL;
        }
        var_dump($points[0]['points']);
        
        $friends = $this->container->get('metinet.manager.fbuser')->getUserFriends("me");

        return array("friends" => $friends['data'],
                    "points" => $points[0]['points'],
                    "totalJoueur" => $totalJoueur[0][1],
                    "averageTime" => $averageTime[0]['averageTime'],
                    "classementJoueur" => $classementJoueur[0][1]
                    );
    }
}
