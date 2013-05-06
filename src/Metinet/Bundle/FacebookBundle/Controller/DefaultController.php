<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

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
        
        return array();
    }
    
    /**
     * @Route("/amis")
     * @Template()
     */
    public function amisAction()
    {
        $friends = $this->container->get('metinet.manager.fbuser')->getUserFriends("me");
        return array("friends" => $friends['data']);
    }
}
