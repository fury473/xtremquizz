<?php

namespace Metinet\XtremQUIZZBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository {

    public function getNbJoueur() {
        return $this->_em->createQuery('
			SELECT
				COUNT(i)
			FROM
				MetinetXtremQUIZZBundle:User i
		');
    }

}
