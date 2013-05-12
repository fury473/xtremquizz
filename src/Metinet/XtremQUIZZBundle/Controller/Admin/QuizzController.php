<?php

namespace Metinet\XtremQUIZZBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class QuizzController extends Controller
{
    /**
     * @Route("/admin/quizz/")
     * @Template()
     */
    public function indexAction()
    {
        
        return array();
    }
}
