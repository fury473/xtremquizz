<?php

namespace Metinet\XtremQUIZZBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * QuizzRepository
 */
class QuizzRepository extends EntityRepository
{
    public function getNbQuizz() {
        return $this->_em->createQuery('
			SELECT
				COUNT(i)
			FROM
				MetinetXtremQUIZZBundle:Quizz i
                        WHERE
                                i.state = 1
		');
    }
    
    public function getNbQuizzJoues() {
        return $this->_em->createQuery('
			SELECT
				COUNT(i)
			FROM
				MetinetXtremQUIZZBundle:Quizz i
                        WHERE
                                i.nbLaunches > 0
		');
    }
    
    public function getTopQuizz(){
        return $this->_em->createQuery('
                SELECT
                        i.id, i.title, i.picture
                FROM
                        MetinetXtremQUIZZBundle:Quizz i
                ORDER BY
                        i.nbLaunches DESC
        ')->setMaxResults(3);
    }
    
    public function getFlopQuizz(){
        return $this->_em->createQuery('
                SELECT
                        i.id, i.title, i.picture
                FROM
                        MetinetXtremQUIZZBundle:Quizz i
                ORDER BY
                        i.nbLaunches ASC
        ')->setMaxResults(3);
    }

    public function getLastQuizzId() {
        return $this->_em->createQuery('
			SELECT
				i.id, i.title, i.picture, i.winPoints
			FROM
				MetinetXtremQUIZZBundle:Quizz i
                        ORDER BY
                                i.createdAt DESC
		')->setMaxResults(4);
    }
    
    public function getQuizzByTheme($idTheme) {
        return $this->_em->createQuery("
                    SELECT
                            i.id, i.title, i.picture
                    FROM
                            MetinetXtremQUIZZBundle:Quizz i
                    WHERE
                            i.theme = $idTheme
            ");
    }
}
