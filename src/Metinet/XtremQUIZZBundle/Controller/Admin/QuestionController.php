<?php

namespace Metinet\XtremQUIZZBundle\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class QuestionController extends Controller
{
    /**
     * @Route("/admin/question/")
     * @Template()
     */
    public function indexAction()
    {
        
        return array();
    }
}
