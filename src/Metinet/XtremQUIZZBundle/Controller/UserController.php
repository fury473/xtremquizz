<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
* @Route("/user")
*/
class UserController extends Controller
{
    /**
     * @Route("/", name="user")
     * @Template()
     */
    public function indexAction()
    {
        
        return array();
    }
    /**
     * Classement
     * 
     * @Route("/ranking", name="ranking")
     * @Template()
     */
    public function rankingAction()
    {
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
        
        // echo var_dump($entities);
        return array("friends" => $friends['data'],
            'entities' => $entities,
            );
    }
}