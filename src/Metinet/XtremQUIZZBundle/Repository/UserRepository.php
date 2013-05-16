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
    
    public function getNbJoueur7jours() {
        $date = date('Y-m-d');
        $NewDate=Date('Y-m-d', strtotime("-7 days"));
        return $this->_em->createQuery("
			SELECT
				COUNT(i)
			FROM
				MetinetXtremQUIZZBundle:User i
                        WHERE
                                i.createdAt BETWEEN '$NewDate' AND '$date'
		");
    }
    
    public function getNbJoueur30jours() {
        $date = date('Y-m-d');
        $NewDate=Date('Y-m-d', strtotime("-30 days"));
        return $this->_em->createQuery("
			SELECT
				COUNT(i)
			FROM
				MetinetXtremQUIZZBundle:User i
                        WHERE
                                i.createdAt BETWEEN '$NewDate' AND '$date'
		");
    }
    
    public function getScoreMoyen(){
        return $this->_em->createQuery("
                        SELECT
                                AVG(i.points)
                        FROM
                                MetinetXtremQUIZZBundle:User i
                ");
    }
    
    public function getRank($limit = null, $offset = 0){
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.points', 'DESC');
        if ($limit) {
            $qb->setMaxResults($limit);
        }
        if ($offset) {
            $qb->setFirstResult($offset);
        }
        return $qb->getQuery()->getResult();
    }
}
