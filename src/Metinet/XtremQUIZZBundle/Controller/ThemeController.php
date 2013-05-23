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
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetXtremQUIZZBundle:Theme')->getListingAlltheme()->execute();
        $i = 0;
        foreach($entities as $theme){
            $nbQuizz = $em->getRepository('MetinetXtremQUIZZBundle:Quizz')->getNbQuizzTheme($theme['id'])->execute();
            $entities[$i]['nbQuizz'] = $nbQuizz[0][1];
            $i++;
        }
        
        return array(
            'entities' => $entities,
        );
    }
}
