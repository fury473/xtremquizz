<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AnswerController extends Controller
{
    /**
     * @Route("/reponse/")
     * @Template()
     */
    public function indexAction()
    {
        
        return array();
    }
}
