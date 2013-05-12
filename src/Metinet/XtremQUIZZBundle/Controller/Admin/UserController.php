<?php

namespace Metinet\XtremQUIZZBundle\Admin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{
    /**
     * @Route("/admin/user/")
     * @Template()
     */
    public function indexAction()
    {
        
        return array();
    }
}
