<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ThemeController extends Controller
{
    /**
     * @Route("/theme/", name="theme")
     * @Template()
     */
    public function indexAction()
    {
    }
}
