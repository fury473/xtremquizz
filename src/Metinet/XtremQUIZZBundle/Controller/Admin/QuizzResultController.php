<?php

namespace Metinet\XtremQUIZZBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class QuizzResultController extends Controller
{
    /**
     * @Route("/admin/quizz-resultat/")
     * @Template()
     */
    public function indexAction()
    {
        
        return array();
    }
}
