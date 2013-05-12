<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class QuizzResultController extends Controller
{
    /**
     * @Route("/quizz-resultat/")
     * @Template()
     */
    public function indexAction()
    {
        
        return array();
    }
}
