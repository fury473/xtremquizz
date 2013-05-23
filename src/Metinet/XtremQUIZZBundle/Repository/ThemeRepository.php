<?php
namespace Metinet\XtremQUIZZBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ThemeRepository
 */
class ThemeRepository extends EntityRepository
{
        public function getListingAlltheme() {
        return $this->_em->createQuery("
                    SELECT
                            i.id, i.picture, i.title, i.shortDesc
                    FROM
                            MetinetXtremQUIZZBundle:Theme i
            ");
    }
    
       public function getThemeByID($id) {
        return $this->_em->createQuery("
                    SELECT
                            i.id, i.picture, i.title, i.shortDesc
                    FROM
                            MetinetXtremQUIZZBundle:Theme i
                    WHERE
                            i.id = $id
            ");
    }
}
