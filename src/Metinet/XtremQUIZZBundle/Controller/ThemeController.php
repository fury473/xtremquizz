<?php

namespace Metinet\XtremQUIZZBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/theme")
 */
class ThemeController extends Controller
{
    /**
     * @Route("/", name="theme")
     * @Template()
     */
    public function indexAction()
    {
    }
}
